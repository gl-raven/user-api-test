<?php

namespace Mapper;


use PDO;

class UserMapper extends AbstractMapper
{
    public static function cryptPassword($password): string
    { 
        return password_hash(
            $password,
            PASSWORD_BCRYPT
        );
    }

    public function findUser($user_id): array|false
    {
        $query = 'SELECT * FROM `user` WHERE `id` = :id LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':id' => $user_id,
        ));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByLogin($login): array|false
    {
        $query = 'SELECT * FROM `user` WHERE `login` = :login LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(
            ':login' => $login,
        ));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($login, $password, $email): int
    {
        $query = 'INSERT INTO `user` (`login`, `password`, `email`) VALUES (:login, :password, :email)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":login", $login);
        $stmt->bindValue(":password", self::cryptPassword($password)); //$password);
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function deleteUser($id): bool
    {
        $query = 'DELETE FROM `user` WHERE `id` = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return (bool) $stmt->rowCount();
    }

    public function updateUser($id, $data)
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "`$key` = :$key";
            $values[":$key"] = $value;
        }

        $query = 'UPDATE `user` SET ' . implode(', ', $fields) . ' WHERE `id` = :id';
        $stmt = $this->db->prepare($query);
        $values[":id"] = $id;
        $stmt->execute($values);

        return (bool) $stmt->rowCount();
    }
}
