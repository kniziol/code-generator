<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints\UniqueCodeValue;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Code
 *
 * @ORM\Table(
 *     name="codes",
 *     indexes={
 *          @ORM\Index(name="code_index", columns={"value"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CodeRepository")
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class Code
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="10")
     * @UniqueCodeValue()
     */
    private $value;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
