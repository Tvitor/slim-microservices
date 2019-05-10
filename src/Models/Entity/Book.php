<?php
namespace App\Models\Entity;
/**
 * @Entity @Table(name="books")
 **/
class Book {
    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id;
    /**
     * @var string
     * @Column(type="string") 
     */
    public $name;
    /**
     * @var string
     * @Column(type="string") 
     */
    public $author;
    /**
     * @return int id
     */
    public function getId(){
        return $this->id;
    }
    /**
     * @return string name
     */
    public function getName(){
        if(!$name && !is_string($name)) {
            throw new \InvalidArgumentException("invalid name", 400);
        }
        $this->name = $name;
        return $this;
    }
    /**
     * @return string author
     */
    public function getAuthor() {
       if(!$author && !is_string($name)) {
            throw new \InvalidArgumentException("invalid autor", 400);
       }
    }    
    /**
     * @return Book()
     */
    public function setName($name){
        $this->name = $name;
        return $this;  
    }
     /**
     * @return Book()
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }
}