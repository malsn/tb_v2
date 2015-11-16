<?php



namespace TooBig\AppBundle\Model;

interface ItemInterface
{
    /**
     * Set title
     *
     * @param string $title
     */
    function setTitle($title);

    /**
     * Get title
     *
     * @return string $title
     */
    function getTitle();

    /**
     * Set abstract
     *
     * @param text $abstract
     */
    function setAbstract($abstract);

    /**
     * Get abstract
     *
     * @return text $abstract
     */
    function getAbstract();

    /**
     * Set content
     *
     * @param text $content
     */
    function setContent($content);

    /**
     * Get content
     *
     * @return text $content
     */
    function getContent();

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    function setEnabled($enabled);

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    function getEnabled();

    /**
     * Set slug
     *
     * @param integer $slug
     */
    function setSlug($slug);

    /**
     * Get slug
     *
     * @return integer $slug
     */
    function getSlug();

    /**
     * Set publication_date_start
     *
     * @param \DateTime $publicationDateStart
     */
    function setPublicationDateStart(\DateTime $publicationDateStart = null);

    /**
     * Get publication_date_start
     *
     * @return \DateTime $publicationDateStart
     */
    function getPublicationDateStart();

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     */
    function setCreatedAt(\DateTime $createdAt = null);

    /**
     * Get created_at
     *
     * @return \DateTime $createdAt
     */
    function getCreatedAt();

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     */
    function setUpdatedAt(\DateTime $updatedAt = null);

    /**
     * Get updated_at
     *
     * @return datetime $updatedAt
     */
    function getUpdatedAt();



    /**
     * Set comments_enabled
     *
     * @param boolean $commentsEnabled
     */
    function setCommentsEnabled($commentsEnabled);

    /**
     * Get comments_enabled
     *
     * @return boolean $commentsEnabled
     */
    function getCommentsEnabled();

    /**
     * Set comments_close_at
     *
     * @param \DateTime $commentsCloseAt
     */
    function setCommentsCloseAt(\DateTime $commentsCloseAt = null);

    /**
     * Get comments_close_at
     *
     * @return datetime $commentsCloseAt
     */
    function getCommentsCloseAt();

    /**
     * Set comments_default_status
     *
     * @param integer $commentsDefaultStatus
     */
    function setCommentsDefaultStatus($commentsDefaultStatus);

    /**
     * Get comments_default_status
     *
     * @return integer $commentsDefaultStatus
     */
    function getCommentsDefaultStatus();

    function isCommentable();

    function setAuthor($author);

    function getAuthor();
}