<?php

namespace App\Traits;

trait Purifiable
{
    /**
     * Updates the content and html attribute of the given model.
     *
     * @param string $rawHtml
     *
     * @return \Illuminate\Database\Eloquent\Model $this
     */
    public function setPurifiedContent($rawHtml)
    {
        // Define allowed HTML tags and attributes
        $allowedTags = '<p><a><strong><em><u><ul><ol><li>';
        /* $allowedAttributes = [
            'a' => 'href',
        ]; */

        // Remove disallowed HTML tags and attributes
        $this->content = strip_tags($rawHtml, $allowedTags);

        // Allow specific attributes for allowed tags
        /* foreach ($allowedAttributes as $tag => $attribute) {
            $this->content = preg_replace("/<$tag[^>]*?($attribute)=[\"\']?[^\"\']*[\"\']?[^>]*?>/i", "<$tag $attribute=\"\$2\">", $this->content);
        } */

        // Set HTML content directly from raw HTML, without any purification
        $this->html = $rawHtml;

        return $this;
    }
}
