<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="text", length=180)
     */
    private $name;

    public function getName() 
    {
        return $this->name;
    }
    public function setName($name) 
    {
        $this->name = $name;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function getDescription() 
    {
        return $this->description;
    }
    public function setDescription($description) 
    {
        $this->description = $description;
    }

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $tag;
}
