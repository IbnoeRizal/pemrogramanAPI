<?php

namespace App\Services;
use \Illuminate\Http\Client\Response;
class Htmlhelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function toAbsoluteUrl(string $url, string $base)
    {
        if (preg_match('#^(data:|javascript:)#i', $url)) return $url;
        if (parse_url($url, PHP_URL_SCHEME)) return $url;

        $baseParts = parse_url($base);
        if (!$baseParts) return $url;

        $scheme = $baseParts['scheme'] ?? 'https';
        $host = $baseParts['host'] ?? null;
        if (!$host) return $url;

        if (substr($url, 0, 1) === '/') {
            return $scheme . '://' . $host . $url;
        }

        $path = dirname($baseParts['path'] ?? '/');
        return $scheme . '://' . $host . rtrim($path, '/') . '/' . ltrim($url, '/');
    }

    // helper: dapatkan outerHTML dari DOMNode
    public function getOuterHtml(\DOMNode $node)
    {
        $doc = $node->ownerDocument;
        $tmp = new \DOMDocument();
        $tmp->appendChild($tmp->importNode($node, true));
        return $tmp->saveHTML();
    }

    //helper: dapatkan DOMDocument
    public function createDom($res){
        // parse dengan DOMDocument
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        // tambahkan encoding supaya karakter aman
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $res->body());
        libxml_clear_errors();

        return $dom;
    }

    public function cleanForm(\DOMNode $node)
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            /** @var \DOMElement $child */
            if ($child->nodeType === XML_TEXT_NODE) {
                $node->removeChild($child);
                continue;
            }

            if ($child->nodeType !== XML_ELEMENT_NODE) continue;

            $tag = strtolower($child->tagName);
            $type = strtolower($child->getAttribute('type'));

            // Jika elemen ini bukan input atau button submit
            if ($tag !== 'input' && !($tag === 'button' && $type === 'submit')) {
                // Bersihkan isi anak-anaknya
                $this->cleanForm($child);

                // Pindahkan semua input dan button submit dari dalam elemen ini ke parent form
                foreach ($child->getElementsByTagName('input') as $input) {
                    //display input as none
                    $input->setAttribute('style', 'display:none !important;');
                    $node->appendChild($input->cloneNode(true));
                }
                foreach ($child->getElementsByTagName('button') as $btn) {
                    if (strtolower($btn->getAttribute('type')) === 'submit') {
                        $node->appendChild($btn->cloneNode(true));
                    }
                }

                // Setelah semua input/button diselamatkan, hapus elemen wrapper-nya
                $node->removeChild($child);
            }
        }
    }

    public function formToArray(\DOMElement $form)
    {
        $data = [];
        foreach ($form->getElementsByTagName('input') as $input) {
            $name = $input->getAttribute('name');
            $value = $input->getAttribute('value');
            if ($name) $data[$name] = $value;
        }
        foreach ($form->getElementsByTagName('textarea') as $textarea) {
            $name = $textarea->getAttribute('name');
            $value = $textarea->textContent;
            if ($name) $data[$name] = $value;
        }
        foreach ($form->getElementsByTagName('select') as $select) {
            $name = $select->getAttribute('name');
            if (!$name) continue;
            $selected = '';
            foreach ($select->getElementsByTagName('option') as $option) {
                if ($option->hasAttribute('selected')) {
                    $selected = $option->getAttribute('value');
                    break;
                }
            }
            $data[$name] = $selected;
        }
        return $data;
    }
}
