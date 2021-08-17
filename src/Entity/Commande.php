<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 * @UniqueEntity(
 * fields= {"email" },
 * message= " L'email que vous avez tapé est déjà utilisé !"
 * )
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email( message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\EqualTo ("Master Card", message="Vous ne pouvez payez qu'avec une Master Card" )
     */
    private $cardname;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min= 16 , max= 16 , minMessage="Votre numero doit contenir 16 chiffre" ,  maxMessage="Votre numero doit contenir 16 chiffre" )
     */
    private $cardnumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCardname(): ?string
    {
        return $this->cardname;
    }

    public function setCardname(string $cardname): self
    {
        $this->cardname = $cardname;

        return $this;
    }

    public function getCardnumber(): ?int
    {
        return $this->cardnumber;
    }

    public function setCardnumber(int $cardnumber): self
    {
        $this->cardnumber = $cardnumber;

        return $this;
    }
}
