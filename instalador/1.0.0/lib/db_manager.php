<?php

class db_manager {
	protected $sentencias = array();
	protected $logger;
	protected $schema;
	protected $cache_metadatos = array(); //Guarda un cache de los metadatos de cada tabla
	
	// Constructor
	public function __construct($logger=null)
	{
		if (isset($logger)) {
			$this->logger = $logger;
		}
	}

	function conectar($parametros) {
		$dsn = "pgsql:host=" . $parametros['profile'] . " port=" . $parametros['puerto'] . " dbname=" . $parametros['base'] . " user=" . $parametros['usuario'] . " password=" . $parametros['clave'];
		$dbh = new PDO($dsn);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if (isset($this->logger)) {
			inst::logger()->debug("Conectando a servidor {$parametros['profile']}, base {$parametros['base']} con usuario {$parametros['usuario']}");
		}
		return $dbh;
	}

	function desconectar($dbh) {
		$dbh = null;
	}

	function ejecutar($dbh, $sql) {
		if (! is_array($sql)) {
			$sql = array($sql);
		}
		foreach($sql as $comando) {
			if (isset($this->logger)) {
				inst::logger()->debug('Ejectuando SQL: '.$comando);
			}
			$dbh->exec($comando);
		}
	}

	function ejecutar_archivo($dbh, $archivo)
	{
		if (!file_exists($archivo)) {
			throw new inst_error("Error al ejecutar comandos. El archivo '$archivo' no existe");
		}
		$str = file_get_contents($archivo);
		return $this->ejecutar($dbh, $str);
	}

	function consultar($dbh, $sql)
	{
		return $dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}

	function consultar_fila($dbh, $sql) {
		$rs = $dbh->query($sql)->fetchAll();
		if(isset($rs[0]))
			return $rs[0];
		return array();
	}


	/**
	*	Prepara una sentencia para su ejecucion posterior.
	*
	*	@param string $sql Consulta SQL
	*	@param array $opciones Arreglo con parametros del driver
	*	@return integer ID de la sentencia, necesario para ejecutarla posteriormente con 'ejecutar_sentencia($id)'
	*	@throws toba_error_db en caso de error
	*/
	function sentencia_preparar($dbh, $sql, $opciones=array())
	{
		$id = count($this->sentencias);
		$this->sentencias[$id] = $dbh->prepare($sql, $opciones);
		if ($this->sentencias[$id] === false ) {
			throw new inst_error("Error preparando la sentencia: $sql");
		}
		return $id;
	}

	/**
	*	Ejecuta una sentencia SQL preparada con 'preparar_sentencia'.
	*
	*	@param integer ID de la sentencia
	*	@param array Arreglo con parametros de la sentencia
	*	@return integer Cantidad de registros afectados
	*	@throws toba_error_db en caso de error
	*/
	function sentencia_ejecutar($dbh, $id, $parametros=array())
	{
		if(!isset($this->sentencias[$id])) {
			throw new inst_error("La sentencia solicitada no existe.");
		}
		$this->sentencias[$id]->execute($parametros);
		return $this->sentencias[$id]->rowCount();
	}

	/**
	 * Convierte un string a una representación segura para el motor. Evita
	 * la inyección de código malicioso dentro de la sentencia SQL
	 * @param mixed $dato Puede ser un string o un arreglo
	 */
	function quote($dbh, $dato)
	{
		if (! is_array($dato)) {
			return $dbh->quote($dato);
		} else {
			$salida = array();
			foreach (array_keys($dato) as $clave) {
				$salida[$clave] = $this->quote($dbh, $dato[$clave]);
			}
			return $salida;
		}
	}

	function abrir_transaccion($dbh) {
		$sql = "BEGIN;";
		$this->ejecutar($dbh, $sql);
	}

	function cerrar_transaccion($dbh) {
		$sql = "END;";
		$this->ejecutar($dbh, $sql);
	}

	function abortar_transaccion($dbh) {
		$sql = "ROLLBACK;";
		$this->ejecutar($dbh, $sql);
	}

	function retrazar_constraints($dbh, $retrazar=true)
	{
		$tipo = $retrazar ? 'DEFERRED' : 'IMMEDIATE';
		$sql = "SET CONSTRAINTS ALL $tipo";
		$this->ejecutar($dbh, $sql);
	}

	function set_schema($dbh, $esquema)
	{
		$this->schema = $esquema;
		$sql = "SET search_path TO $esquema, public";
		$this->ejecutar($dbh, $sql);
	}

	function set_encoding($dbh, $encoding)
	{
		$sql = "SET CLIENT_ENCODING TO '$encoding'";
		$this->ejecutar($dbh, $sql);
	}

	//-----------------------------------------------------------------------
	//-------- METADATA
	//-----------------------------------------------------------------------

	function get_schema()
	{
		if (isset($this->schema)) {
			return $this->schema;
		}
	}
	
	//---- CONSULTAS
	function existe_tabla($dbh, $esquema, $tabla) {

		$sql = "SELECT
					table_name
				FROM
					information_schema.tables
				WHERE
					table_name = '" . $tabla. "' AND
					table_schema= '". $esquema . "';";

		$rs = $this->consultar_fila($dbh, $sql);

		if(empty($rs))
			return false;
		return true;
	}

	function existe_schema($dbh, $esquema) {

		$sql = "SELECT
					count(*) as cant
				FROM
					information_schema.schemata
				WHERE
					schema_name = '$esquema'
		";
		$rs = $this->consultar_fila($dbh, $sql);
		return $rs['cant'] > 0;
	}

	function existe_rol($dbh, $usuario) {

		$sql = "SELECT
					COUNT(*) AS cantidad
				FROM
					information_schema.enabled_roles
				WHERE
					role_name = '" . $usuario . "';";

		$rs = $this->consultar_fila($dbh, $sql);
		return $rs['cantidad'] > 0;
	}

	function get_tablas($dbh, $esquema)
	{
		$sql = "SELECT
					relname AS nombre
				FROM
					pg_catalog.pg_class c
						LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
						LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
				WHERE
					c.relkind = 'r'::\"char\" AND
					n.nspname = '$esquema'
				ORDER BY
					relname";
		return $this->consultar($dbh, $sql);
	}
	
	function get_secuencia_tablas($dbh, $tablas, $schema = null)
	{
		$secuencias = array();
		$tablas_usadas = implode ("','" , $tablas);			
		if (is_null($schema)) {
			$schema = $this->get_schema();
		}	

		$sql = "
			SELECT column_name, table_name
			FROM information_schema.columns
			WHERE
				table_name   IN ('$tablas_usadas')
				AND	table_schema = '$schema'
				AND ((column_default LIKE '%seq\"''::text)::regclass)') OR (column_default LIKE '%seq''::text)::regclass)')); ";

		$result = $this->consultar($dbh, $sql);
		if (! empty($result)) {
			foreach($result as $valores) {
				$secuencias[$valores['table_name']] = $valores['column_name'];
			}
		}
		return $secuencias;
	}

	function get_vistas($dbh, $esquema) {

		$sql = "SELECT
					relname AS nombre
				FROM
					pg_catalog.pg_class c
						LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
						LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
				WHERE
					c.relkind = 'v'::\"char\" AND
					n.nspname = '$esquema'
				ORDER BY
					relname;";
		return $this->consultar($dbh, $sql);
	}

	function get_secuencias($dbh, $esquema)
	{
		$sql = "SELECT
					relname AS nombre
				FROM
					pg_catalog.pg_class c
						LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
						LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
				WHERE
					c.relkind = 'S'::\"char\" AND
					n.nspname = '$esquema'
				ORDER BY
					relname
		";
		return $this->consultar($dbh, $sql);
	}


	//---- COMANDOS
	function crear_base($dbh, $base, $encoding=null, $extra=null)
	{
		$sql_encoding = '';
		if (isset($encoding)) {
			$sql_encoding =  " ENCODING '$encoding'";
		}
		if (is_null($extra)) {
			$extra = '';
		}
		$sql = "CREATE DATABASE \"" . $base.'"'. $sql_encoding . ' '. $extra;
		return $this->ejecutar($dbh, $sql);
	}

	function borrar_base($dbh, $base)
	{
		$sql = "DROP DATABASE \"$base\";";
		return $this->ejecutar($dbh, $sql);
	}

	function borrar_schema($dbh, $schema)
	{
		$sql = "DROP SCHEMA \"$schema\" CASCADE";
		return $this->ejecutar($dbh, $sql);
	}

	function renombrar_schema($dbh, $desde, $hacia)
	{
		$sql = "ALTER SCHEMA \"$desde\" RENAME TO \"$hacia\"";
		return $this->ejecutar($dbh, $sql);
	}

	function crear_usuario($dbh, $usuario, $clave) {

		$sql = "CREATE USER " . $usuario . " PASSWORD '" . $clave . "';";
		$this->ejecutar($dbh, $sql);
	}

	function crear_rol($dbh, $rol) {

		$sql = "CREATE ROLE $rol";
		$this->ejecutar($dbh, $sql);
	}

	function grant_base($dbh, $base, $usuario, $privilegios = 'ALL PRIVILEGES')
	{
		$sql = "GRANT $privilegios ON DATABASE \"$base\" TO $usuario";
		$this->ejecutar($dbh, $sql);
	}

	function grant_rol($dbh, $rol, $usuario)
	{
		$sql = "GRANT $rol TO $usuario";
		$this->ejecutar($dbh, $sql);
	}

	function cambiar_search_path($dbh, $rol, $nuevo)
	{
		$sql = "ALTER ROLE $rol SET search_path=$nuevo";
		$this->ejecutar($dbh, $sql);
	}

	function owner_base($dbh, $base, $usuario)
	{
		$sql = "ALTER DATABASE \"$base\" OWNER TO $usuario";
		$this->ejecutar($dbh, $sql);
	}

	function owner_schema($dbh, $schema, $usuario)
	{
		$sql = "ALTER SCHEMA \"$schema\" OWNER TO $usuario";
		$this->ejecutar($dbh, $sql);
	}

	function owner_objeto_de_tabla($dbh, $schema, $objeto, $usuario)
	{
		$sql = "ALTER TABLE $schema.$objeto OWNER TO $usuario;";
		$this->ejecutar($dbh, $sql);
	}

	function owner_funciones($dbh, $schema, $usuario)
	{
		$sql = "UPDATE
					pg_proc
				SET
					proowner = (select usesysid from pg_user where usename='$usuario')
				WHERE
					pronamespace = (SELECT
										oid
									FROM
										pg_namespace
									WHERE
										nspname = '$schema')
		";
		$this->ejecutar($dbh, $sql);
	}

	function grant_language($dbh, $lenguaje, $usuario)
	{
		$sql = "GRANT USAGE ON LANGUAGE $lenguaje TO $usuario";
		$this->ejecutar($dbh, $sql);
	}

	function grant_schema($dbh, $usuario, $schema)
	{
		$sql = "GRANT ALL ON SCHEMA \"$schema\" TO $usuario";
		$this->ejecutar($dbh, $sql);
	}

	/**
	 *	Da permisos especificos a todas las tablas de un esquema dado
	 **/
	function grant_tablas_schema($dbh, $usuario, $schema, $privilegios ='ALL PRIVILEGES')
	{
		$sql = "SELECT
					relname
				FROM pg_class c
					JOIN pg_namespace ns ON (c.relnamespace = ns.oid)
				WHERE
						relkind in ('r','v','S')
					AND nspname = '$schema'
		";
		$rs = $this->consultar($dbh, $sql);
		foreach ($rs as $tabla) {
			$sql = "GRANT $privilegios ON $schema.{$tabla['relname']} TO $usuario";
			$this->ejecutar($dbh, $sql);
		}
	}

	function grant_secuencias_schema($dbh, $usuario, $schema, $privilegios = 'ALL PRIVILEGES')
	{
		$sql = "SELECT
					relname
				FROM pg_class c
					JOIN pg_namespace ns ON (c.relnamespace = ns.oid)
				WHERE
						relkind in ('S')
					AND nspname = '$schema'
		";
		$rs = $this->consultar($dbh, $sql);
		if(!empty($rs)) {
			foreach ($rs as $secuencia) {
				$sql = "GRANT $privilegios ON $schema.{$secuencia['relname']} TO $usuario";
				$this->ejecutar($dbh, $sql);
			}
		}
	}

	/**
	 * Da permisos al schema original y schema de auditoria si existe
	 */
	 function grant_proyecto($dbh, $usuario, $schema, $superusuario)
	{
		//-- Actualiza los permisos del usuario de la conexion
		inst::db_manager()->grant_schema($dbh, $usuario, $schema);
		inst::db_manager()->grant_tablas_schema($dbh, $usuario, $schema);
		/*
		//-- Owner de tablas a superusuario
		foreach($this->get_tablas($dbh, $schema) as $tabla) {
			$this->owner_objeto_de_tabla($dbh, $schema, $tabla['nombre'], $superusuario);
		}

		//-- Owner de vistas a superusuario
		foreach($this->get_vistas($dbh, $schema) as $vista) {
			$this->owner_objeto_de_tabla($dbh, $schema, $vista['nombre'], $superusuario);
		}

		//-- Owner de secuencias a superusuario
		foreach($this->get_secuencias($dbh, $schema) as $secuencia) {
			$this->owner_objeto_de_tabla($dbh, $schema, $secuencia['nombre'], $superusuario);
		}

		//-- Owner de funciones a superusuario
		$this->owner_funciones($dbh, $schema, $superusuario);
		*/
		//-- Actualiza los permisos de auditoria si existe
		$schema_auditoria = $schema."_auditoria";
		if (inst::db_manager()->existe_schema($dbh, $schema_auditoria)) {
			inst::db_manager()->grant_schema($dbh, $usuario, $schema_auditoria);
			inst::db_manager()->grant_tablas_schema($dbh, $usuario, $schema_auditoria, "SELECT, INSERT");
			inst::db_manager()->grant_secuencias_schema($dbh, $usuario, $schema_auditoria, "SELECT, USAGE");
		}
	}

/**
	 *  Devuelve una lista de los triggers en el esquema, segun estado, nombre y tablas.
	 * @param string $schema	Nombre del schema
	 * @param string $nombre	Comienzo del nombre de/los triggers
	 * @param char $estado		Estado de disparo actual del trigger (O=Origen, D=Disable, A=Always, R=Replica)
	 * @param array $tablas		Tablas involucradas con los triggers
	 * @return array
	 */
	function get_triggers_schema($dbh, $schema, $nombre = '', $estado = 'O', $tablas = array())
	{
		$where = array();
		$esquema = $this->quote($dbh,$schema);
		$estado = $this->quote($dbh, $estado);
		$sql = "  SELECT t.*, 
					     c.relname as tabla, 
				              n.nspname as schema
				FROM pg_trigger as t,
				            pg_class as c, 
					   pg_namespace as n 
				WHERE  
					  t.tgrelid = c.oid 
					  AND c.relnamespace = n.oid 
					  AND n.nspname = $esquema
					  AND t.tgenabled = $estado ";
		
		if (trim($nombre) != '') {
			$sql .= ' AND t.tgname ILIKE '. $this->quote($dbh, $nombre.'%');
		}		
		if (! empty($tablas)) {
			$tablas = $this->quote($dbh, $tablas);
			$sql  .= ' AND C.relname IN ('. implode(',', $tablas) . ')';
		}
		return $this->consultar($dbh, $sql);
	}	
	
	
	function crear_schema($dbh, $esquema) {

		$sql = "CREATE SCHEMA \"$esquema\" ";
		return $this->ejecutar($dbh, $sql);
	}

	function crear_lenguaje($dbh, $lenguaje)
	{
		$sql = "SELECT lanname FROM pg_language WHERE lanname='$lenguaje'";
		$rs = $this->consultar($dbh, $sql);
		if (empty($rs)) {
			$sql = "CREATE LANGUAGE $lenguaje";
			$this->ejecutar($dbh, $sql);
		}
	}	
	
	static function get_version_motor_db($dbh)
	{
		return $dbh->getAttribute(PDO::ATTR_SERVER_VERSION);
	}
	
	function get_lista_secuencias($dbh, $esquema=null)
	{
		$where = '';
		if (! is_null($esquema)) {
			$esquema = $this->quote($dbh, $esquema);
			$where .= " AND n.nspname = $esquema" ;			
		} else{ //if (isset($this->$schema)) {
			$esquema = $this->quote($dbh, $this->get_schema());
			//$esquema = $this->quote($dbh, $this->$schema);
			$where .= " AND n.nspname = $esquema" ;			
		}
		
		$sql = "
			SELECT 
				c.relname as tabla,
				a.attname as campo,
				replace( substring(adef.adsrc,'''[^'']*'''), '''', '' ) as nombre
			FROM
				pg_catalog.pg_attribute a 
					LEFT JOIN pg_catalog.pg_attrdef adef ON a.attrelid=adef.adrelid AND a.attnum=adef.adnum
					LEFT JOIN pg_catalog.pg_type t ON a.atttypid=t.oid
					LEFT JOIN pg_catalog.pg_class c ON a.attrelid=c.oid
					LEFT JOIN pg_catalog.pg_namespace as n ON c.relnamespace = n.oid
			WHERE
			 	adsrc like '%nextval%'
			 	AND a.attnum > 0 AND NOT a.attisdropped
			 	$where
			ORDER BY a.attname;
		";
		return $this->consultar($dbh, $sql);
	}
	
	function get_definicion_columnas($conexion, $tabla, $esquema=null)
	{
		if (isset($this->cache_metadatos[$tabla])) {
			return $this->cache_metadatos[$tabla];
		}		
		$where = '';
		$tabla_sana = $this->quote($conexion, $tabla);		
		if (isset($esquema)) {
			$esquema = $this->quote($conexion, $esquema);
			$where .= " AND n.nspname = $esquema" ;
		}
		//1) Busco definicion
		$sql = "SELECT  a.attname as 			nombre,
						t.typname as 			tipo,
						a.attlen as 			tipo_longitud,
						a.atttypmod as 			longitud,
						format_type(a.atttypid, a.atttypmod) as tipo_sql,
						a.attnotnull as 		not_null,
						a.atthasdef as 			tiene_predeterminado,
						d.adsrc as 				valor_predeterminado,
						'' as					secuencia,
						fc.relname				as fk_tabla,
						fa.attname				as fk_campo,						
						a.attnum as 			orden,
						c.relname as			tabla,
						EXISTS (SELECT 
									indisprimary 
								FROM 
									pg_index i INNER JOIN pg_class ic ON ic.oid = i.indexrelid AND indisprimary = TRUE 
								WHERE 
									(	a.attrelid = i.indrelid 
								     AND (i.indkey[0] = a.attnum 
										OR i.indkey[1] = a.attnum 
										OR i.indkey[2] = a.attnum 
										OR i.indkey[3] = a.attnum 
										OR i.indkey[4] = a.attnum 
										OR i.indkey[5] = a.attnum 
										OR i.indkey[6] = a.attnum 
										OR i.indkey[7] = a.attnum)
									)
								) as pk,
						EXISTS (SELECT 
									indisunique 
								FROM 
									pg_index i INNER JOIN pg_class ic ON ic.oid = i.indexrelid AND indisunique = TRUE 
								WHERE 
									( 	a.attrelid = i.indrelid 
								     AND (i.indkey[0] = a.attnum 
										OR i.indkey[1] = a.attnum 
										OR i.indkey[2] = a.attnum 
										OR i.indkey[3] = a.attnum 
										OR i.indkey[4] = a.attnum 
										OR i.indkey[5] = a.attnum 
										OR i.indkey[6] = a.attnum 
										OR i.indkey[7] = a.attnum)
									)
								) as uk
				FROM 	pg_class c,
						pg_type t,
						pg_namespace as n,						
						pg_attribute a 	
							LEFT OUTER JOIN pg_attrdef d
								ON ( d.adrelid = a.attrelid AND d.adnum = a.attnum)
							--- Foreign Keys
							LEFT OUTER JOIN (pg_constraint const 
												INNER JOIN pg_class fc ON fc.oid = const.confrelid
												INNER JOIN pg_attribute fa ON (fa.attrelid = const.confrelid AND fa.attnum = const.confkey[1]
																				AND const.confkey[2] IS NULL) 
											)
								ON (const.conrelid = a.attrelid
										AND const.contype='f'
										AND const.conkey[1] = a.attnum
								)
				WHERE 
						c.relkind in ('r','v') 
					AND c.relname=$tabla_sana
					AND a.attname not like '....%%'
					AND a.attnum > 0 
					AND a.atttypid = t.oid 
					AND a.attrelid = c.oid 
					AND c.relnamespace = n.oid
						$where
				ORDER BY a.attnum;";

		$columnas = $this->consultar($conexion, $sql);
		if (!$columnas) {
			throw new inst_error("La tabla '$tabla' no existe");	
		}
		//2) Normalizo VALORES
		$columnas_booleanas = array('uk','pk','not_null','tiene_predeterminado');		
		foreach(array_keys($columnas) as $id) {
			//Estas columnas manejan string en vez de booleanos
			foreach($columnas_booleanas as $x) {
				$columnas[$id][$x] = ($columnas[$id][$x] == 't');
			}
			
			//Tipo de datos generico
			$columnas[$id]['tipo'] = self::get_tipo_datos_generico($columnas[$id]['tipo']);
			//longitudes
			//-- Si el tipo es -1 es que es 'varlena' http://www.postgresql.org/docs/7.4/static/catalog-pg-type.html
			//-- Para el caso de varchar hay que restarle 4
			if($columnas[$id]['tipo_longitud'] <= 0) {
				$columnas[$id]['longitud'] = $columnas[$id]['longitud'] - 4;
			}
			//-- Si es numerico(a,b) la longitud es 327680*b+a, pero para facilitar el proceso general se usa -1
			if ($columnas[$id]['tipo'] == 'numeric') {
				$columnas[$id]['longitud'] = -1;
			}
			//Secuencias
			if($columnas[$id]['tiene_predeterminado']) {
				$match = array();
				if(preg_match("&nextval.*?(\'|\")(.*?[.]|)(.*)(\'|\")&",$columnas[$id]['valor_predeterminado'],$match)){
					$columnas[$id]['secuencia'] = $match[3];
				}			
			}
		}
		$this->cache_metadatos[$tabla] = array_values($columnas);
		return $this->cache_metadatos[$tabla];
	}

	/**
	*	Mapea un tipo de datos especifico de un motor a uno generico de toba
	*	Adaptado de ADOdb
	*/
	static function get_tipo_datos_generico($tipo)
	{
		$tipo = strtoupper($tipo);
		static $typeMap = array(
			'VARCHAR' => 'C',
			'VARCHAR2' => 'C',
			'CHAR' => 'C',
			'C' => 'C',
			'STRING' => 'C',
			'NCHAR' => 'C',
			'NVARCHAR' => 'C',
			'VARYING' => 'C',
			'BPCHAR' => 'C',
			'CHARACTER' => 'C',
			'INTERVAL' => 'C',  # Postgres
			##
			'LONGCHAR' => 'X',
			'TEXT' => 'X',
			'NTEXT' => 'X',
			'M' => 'X',
			'X' => 'X',
			'CLOB' => 'X',
			'NCLOB' => 'X',
			'LVARCHAR' => 'X',
			##
			'BLOB' => 'B',
			'IMAGE' => 'B',
			'BINARY' => 'B',
			'VARBINARY' => 'B',
			'LONGBINARY' => 'B',
			'BYTEA' => 'B',
			'B' => 'B',
			##
			'YEAR' => 'F', // mysql
			'DATE' => 'F',
			'D' => 'F',
			##
			'TIME' => 'T',
			'TIMESTAMP' => 'T',
			'DATETIME' => 'T',
			'TIMESTAMPTZ' => 'T',
			'T' => 'T',
			##
			'BOOL' => 'L',
			'BOOLEAN' => 'L', 
			'BIT' => 'L',
			'L' => 'L',
			# SERIAL... se tratan como enteros#
			'COUNTER' => 'E',
			'E' => 'E',
			'SERIAL' => 'E', // ifx
			'INT IDENTITY' => 'E',
			##
			'INT' => 'E',
			'INT2' => 'E',
			'INT4' => 'E',
			'INT8' => 'E',
			'INTEGER' => 'E',
			'INTEGER UNSIGNED' => 'E',
			'SHORT' => 'E',
			'TINYINT' => 'E',
			'SMALLINT' => 'E',
			'E' => 'E',
			##
			'LONG' => 'N', // interbase is numeric, oci8 is blob
			'BIGINT' => 'N', // this is bigger than PHP 32-bit integers
			'DECIMAL' => 'N',
			'DEC' => 'N',
			'REAL' => 'N',
			'DOUBLE' => 'N',
			'DOUBLE PRECISION' => 'N',
			'SMALLFLOAT' => 'N',
			'FLOAT' => 'N',
			'FLOAT8' => 'N',
			'NUMBER' => 'N',
			'NUM' => 'N',
			'NUMERIC' => 'N',
			'MONEY' => 'N',

			## informix 9.2
			'SQLINT' => 'E', 
			'SQLSERIAL' => 'E', 
			'SQLSMINT' => 'E', 
			'SQLSMFLOAT' => 'N', 
			'SQLFLOAT' => 'N', 
			'SQLMONEY' => 'N', 
			'SQLDECIMAL' => 'N', 
			'SQLDATE' => 'F', 
			'SQLVCHAR' => 'C', 
			'SQLCHAR' => 'C', 
			'SQLDTIME' => 'T', 
			'SQLINTERVAL' => 'N', 
			'SQLBYTES' => 'B', 
			'SQLTEXT' => 'X' 
		);
		
		if(isset($typeMap[$tipo])) {
			return $typeMap[$tipo];
		}
		return 'Z';
	}
}

?>