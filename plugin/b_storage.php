<?php
/**
 * Banco de dados
 */
class Storage {
    /**
     * Configurações
     */
    public $hostname = '',
           $username = '',
           $password = '',
           $database = '',
           $encoding = 'utf8';

    /**
     * Execução
     */
    private $history,
            $last;

    /**
     * Inicialização
     */
    public function __construct() {
        if(!empty($this->hostname) && !empty($this->username) && !empty($this->password)) {
            self::connect();

            if(!empty($this->database)) {
                self::select_database();
            }

            if(!empty($this->encoding)) {
                self::set_encoding();
            }
        }
    }

    /**
     * Término
     */
    public function __destruct() {
        self::disconnect();
    }

    /**
     * Conexão
     */
    public function connect() {
        if(!$this->connect = mysqli_connect($this->hostname, $this->username, $this->password)) {
            throw new Exception('[MYSQLI] Não foi possível conectar no servidor ' . $this->hostname . ' utilizando o usuário ' . $this->username . '.');
        }
    }

    /**
     * Desconexão
     */
    public function disconnect() {
        return mysqli_close($this->connect);
    }

    /**
     * Database
     */
    public function select_database() {
        if(!mysqli_select_db($this->connect, $this->database)) {
            throw new Exception('[MYSQLI] Erro ao selecionar a base de dados: ' . $this->database);
        }
    }

    /**
     * Codificação
     */
    public function set_encoding() {
        if(!mysqli_query($this->connect, "SET character_set_results = '" . $this->encoding . "', NAMES '" . $this->encoding .  "', character_set_connection = '" . $this->encoding . "', character_set_database = '" . $this->encoding . "', character_set_server = '" . $this->encoding . "';")) {
            throw new Exception('[MYSQLI] Erro ao definir o conjunto de caracteres: ' . $this->encoding);
        }
    }

    /**
     * Consulta
     */
    public function query($value) {
        if(!isset($this->connect)) {
            self::__construct();
        }

        if($value == 'LAST') {
            if(!$this->query = mysqli_query($this->connect, $this->last)) {
                return $this;
            }

        $this->history[] = [date('Y/m/d H:i:s'), $this->last];
        }
        else {
            if(!$this->query = mysqli_query($this->connect, $value)) {
                throw new Exception('[MYSQLI] Erro ao efetuar a consulta: ' . $value);
            }
            $this->history[] = [date('Y/m/d H:i:s'), $value];
        }

        $this->last = $value;
        return $this;
    }

    /**
     * Consulta por matriz
     */
    public function query_array($array) {} # (Table, Action, Input, Condition, Check, Output)

    /**
     * Inserção por matriz
     */
    public function insert_array($table, $array) {
        foreach($array as $key => $value) {
            $columns[] = '`' . $key . '`';
            if($value !== '') {
                $fields[] = "'" . $value . "'";
            }
            else {
                $fields[] = "''";
            }
        }
        $columns = implode(',', $columns);
        $fields = implode(',', $fields);
        self::query("INSERT IGNORE INTO " . $table . " (" . $columns . ") VALUES (" . $fields . ");");
    }

    /**
     * Matriz associativa e númerica
     */
    public function fetch_array() {
        while($array[] = $this->query->fetch_array(MYSQLI_BOTH));
            return array_filter($array);
    }

    /**
     * Matriz associativa
     */
    public function fetch_assoc() {
        while($array[] = $this->query->fetch_array(MYSQLI_ASSOC));
            return array_filter($array);
    }

    /**
     * Matriz númerica
     */
    public function fetch_row() {
        while($array[] = $this->query->fetch_array(MYSQLI_NUM));
            return array_filter($array);
    }

    /**
     * Colunas
     */
    public function fetch_field() {
        while($string = $this->query->fetch_field()->name) {
            $array[] = $string;
        }

        return array_filter($array);
    }

    /**
     * Linhas
     */
    public function num_rows() {
        return mysqli_num_rows($this->query);
    }

    /**
     * Campos
     */
    public function num_fields() {
        return mysqli_field_count($this->connect);
    }

    /**
     * Linhas afetadas
     */
    public function affected_rows() {
        return mysqli_affected_rows($this->connect);
    }

    /**
     * Último ID
     */
    public function insert_id() {
        return mysqli_insert_id($this->connect);
    }

    /**
     * Histórico
     */
    public function get_history() {
        return $this->history;
    }

    /**
     * Próximo
     */
    public function next_result() {
        if(is_object($this->connect)) {
            return mysqli_next_result($this->connect);
        }
    }

    /**
     * Liberar memória
     */
    public function free_result() {
        return mysqli_free_result($this->query);
    }

}
