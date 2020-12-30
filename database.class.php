<?php
require_once('lang.php');

class database
{
    protected $host = '', $user = '', $password = '', $name = '', $table = '';
    protected $connection = null, $statement = null;
    protected $limit = 12, $offset = 0;
    // limit: lấy bao nhiêu bản ghi
    // offset lấy từ bản ghi thứ offset + 1

    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->name = $config['name'];

        $this->connect();
        $this->charset();
    }

    protected function connect()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->name);

        if ($this->connection->connect_errno) {
            exit($this->connection->connect_error);
        }
    }

    protected function charset()
    {
        $this->connection->set_charset("utf8");
    }

    public function table($table_name)
    {
        $this->table = $table_name;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function query($sql)
    {
        $this->statement = $this->connection->prepare($sql) or exit(lang::ERROR_PREPARE_SQL);
        $this->statement->execute() or exit(lang::ERROR_EXECUTE_SQL);
        $this->reset_query();

        $result = $this->statement->get_result();

        $data = [];

        if (!is_object($result)) return;

        while ($row = $result->fetch_object())  $data[] = $row;

        return $data;
    }

    protected function reset_query()
    {
        $this->table = '';
        $this->limit = 12;
        $this->offset = 0;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM $this->table";

        $this->statement = $this->connection->prepare($sql) or exit(lang::ERROR_GET);
        $this->statement->execute() or exit(lang::ERROR_GET);
        $this->reset_query();

        $result = $this->statement->get_result();

        $data = [];

        while ($row = $result->fetch_object())  $data[] = $row;

        return $data;
    }

    public function get()
    {
        $sql = "SELECT * FROM $this->table LIMIT ? OFFSET ?";

        $this->statement = $this->connection->prepare($sql) or exit(lang::ERROR_GET);
        $this->statement->bind_param('ii', $this->limit, $this->offset);
        $this->statement->execute() or exit(lang::ERROR_GET);
        $this->reset_query();

        $result = $this->statement->get_result();

        if (!$result->num_rows) return 'offset was larger';

        $data = [];

        while ($row = $result->fetch_object())  $data[] = $row;

        return $data;
    }

    public function insert($data = [])
    {
        //lấy key của data chuyển mảng key thành chuỗi phân cách bằng ,
        $keyStr = implode(',', array_keys($data));

        //tạo 1 mảng gồm count($data) phần tử key bắt đầu từ 0 và value = ? và sau đó nối thành chuỗi
        $valueStr = implode(',', array_fill(0, count($data), '?'));

        $values = array_values($data);

        //tạo ra chuỗi gồm count($data) chữ s (string )
        $type_values = str_repeat('s', count($data));

        $sql = "INSERT INTO $this->table ($keyStr) VALUES ($valueStr)";

        $this->statement = $this->connection->prepare($sql) or exit(lang::ERROR_INSERT);
        $this->statement->bind_param($type_values, ...$values);
        $this->statement->execute() or exit(lang::ERROR_INSERT);
        $this->reset_query();

        return $this->statement->affected_rows;
    }

    public function update($condition, $data = [])
    {
        $column = implode('', array_keys($condition));
        $column_value = implode('', array_values($condition));

        $key_data = [];

        foreach ($data as $k => $d) $key_data[] = $k . '=?';

        $key_data = implode(',', $key_data);
        $data_value = array_values($data);
        $data_value[] = $column_value;

        $bind_param = str_repeat('s', count($data));
        $bind_param .= $column === 'id' ? 'i' : 's';

        $sql = "UPDATE $this->table SET $key_data WHERE $column = ?";

        $this->statement = $this->connection->prepare($sql) or exit(lang::ERROR_UPDATE);
        $this->statement->bind_param($bind_param, ...$data_value);
        $this->statement->execute() or exit(lang::ERROR_UPDATE);
        $this->reset_query();

        return $this->statement->affected_rows;
    }

    public function delete($condition)
    {
        $column = implode('', array_keys($condition));
        $column_value = implode('', array_values($condition));

        $sql = "DELETE FROM $this->table WHERE $column = ?";

        $this->statement = $this->connection->prepare($sql) or exit(lang::ERROR_DELETE);
        $this->statement->bind_param($column === 'id' ? 'i' : 's', $column_value);
        $this->statement->execute() or exit(lang::ERROR_DELETE);
        $this->reset_query();

        return $this->statement->affected_rows;
    }
}
