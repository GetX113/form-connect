<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vue = false;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $envoyer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formateur", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Societe", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $societe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->date_message;
    }

    public function setDateMessage(\DateTimeInterface $date_message): self
    {
        $this->date_message = $date_message;

        return $this;
    }

    public function getVue(): ?bool
    {
        return $this->vue;
    }

    public function setVue(bool $vue): self
    {
        $this->vue = $vue;

        return $this;
    }

    public function getEnvoyer(): ?string
    {
        return $this->envoyer;
    }

    public function setEnvoyer(string $envoyer): self
    {
        $this->envoyer = $envoyer;

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }
}
