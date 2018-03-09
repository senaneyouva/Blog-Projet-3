<?php

namespace MicroCMS\Domain;

class Article 
{
    /**
     * Id de l'article.
     *
     * @var integer
     */
    private $id;

    /**
     * titre de l'article
     *
     * @var string
     */
    private $title;

    /**
     * contenu de l'article
     *
     * @var string
     */
    private $content;

    /**
     * date de l'article
     *
     * @var datetime
     */
    private $date;


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }


}
