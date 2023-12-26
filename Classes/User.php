<?php
class User {
    protected $id;
    protected $name;
    protected $telephone;

    public function __construct($id, $name, $telephone) {
        $this->id = $id;
        $this->name = $name;
        $this->telephone = $telephone;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getTelephone() {
        return $this->telephone;
    }
}

class Patient extends User {

    protected $address;

    public function __construct($id, $name, $telephone, $address) {

        parent::__construct(
            $id,
            $name,
            $telephone
        );

        $this->address = $address;
    }

    public function getAddress() {
        return $this->address;
    }

}

?>
