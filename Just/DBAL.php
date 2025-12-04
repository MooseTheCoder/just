<?php namespace Just;

/**
 * Lightweight Micro-DBAL for MySQLi
 * - DB::select()
 * - DB::insert()
 * - DB::update()
 * - DB::delete()
 * - DB::query() for raw SQL
 * - Transactions: DB::begin(), DB::commit(), DB::rollback()
 */

class DBAL
{
    private static ?\mysqli $conn = null;

    /** Initialize connection (call once on bootstrap) */
    public static function init(string $host, string $user, string $pass, string $db, int $port = 3306): void
    {
        $mysqli = new \mysqli($host, $user, $pass, $db, $port);

        if ($mysqli->connect_errno) {
            throw new \RuntimeException("DB Connection failed: " . $mysqli->connect_error);
        }

        $mysqli->set_charset('utf8mb4');
        self::$conn = $mysqli;
    }

    /** Build param types string automatically (i, d, s) */
    private static function makeTypes(array $params): string
    {
        return implode('', array_map(function ($p) {
            return is_int($p) ? 'i' : (is_float($p) ? 'd' : 's');
        }, $params));
    }

    /** Prepare, bind, execute, return mysqli_stmt */
    private static function run(string $sql, array $params = []): \mysqli_stmt
    {
        if (!self::$conn) {
            throw new \RuntimeException("DB::init() was not called.");
        }

        $stmt = self::$conn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("SQL prepare failed: " . self::$conn->error);
        }

        if (!empty($params)) {
            $types = self::makeTypes($params);
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException("SQL execute failed: " . $stmt->error);
        }

        return $stmt;
    }

    /** SELECT → returns array */
    public static function select(string $sql, array $params = []): array
    {
        $stmt = self::run($sql, $params);
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** SELECT ONE → returns array|null */
    public static function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = self::run($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ?: null;
    }

    /** INSERT → returns last insert ID */
    public static function insert(string $sql, array $params = []): int
    {
        self::run($sql, $params);
        return self::$conn->insert_id;
    }

    /** UPDATE → returns affected rows */
    public static function update(string $sql, array $params = []): int
    {
        $stmt = self::run($sql, $params);
        return $stmt->affected_rows;
    }

    /** DELETE → returns affected rows */
    public static function delete(string $sql, array $params = []): int
    {
        $stmt = self::run($sql, $params);
        return $stmt->affected_rows;
    }

    /** Raw query (returns mysqli_stmt) */
    public static function query(string $sql, array $params = []): \mysqli_stmt
    {
        return self::run($sql, $params);
    }

    /** Transaction helpers */
    public static function begin(): void
    {
        self::$conn->begin_transaction();
    }

    public static function commit(): void
    {
        self::$conn->commit();
    }

    public static function rollback(): void
    {
        self::$conn->rollback();
    }
}
