<?php

class User
{

    private $db;


    public function __construct($database)
    {
        $this->db = $database;
    }



    public function create(
        $name,
        $email,
        $password
    )
    {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $sql = "
        INSERT INTO users
        (
            name,
            email,
            password
        )

        VALUES
        (
            ?,
            ?,
            ?
        )
        ";


        $stmt = $this->db->prepare($sql);


        return $stmt->execute([

            $name,
            $email,
            $hash

        ]);

    }

    public function findByEmail($email)
{

    $stmt = $this->db->prepare(

        "SELECT 
            id,
            NAME AS name,
            email,
            PASSWORD AS password,
            role
         FROM users
         WHERE email=?"

    );


    $stmt->execute([$email]);


    return $stmt->fetch(PDO::FETCH_ASSOC);

}


    public function find($id)
    {

        $stmt = $this->db->prepare(

            "SELECT id,name,email,phone,role
             FROM users
             WHERE id=?"

        );


        $stmt->execute([$id]);


        return $stmt->fetch();

    }




    public function update(
        $id,
        $name,
    )
    {

        $stmt = $this->db->prepare(

            "UPDATE users
             SET name=?
             WHERE id=?"

        );


        return $stmt->execute([

            $name,
            $id

        ]);

    }




    public function all()
    {

        $stmt=$this->db->prepare(

            "SELECT id,name,email,role
             FROM users
             ORDER BY id DESC"

        );


        $stmt->execute();


        return $stmt->fetchAll();

    }


}

?>