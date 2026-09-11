<?php

declare(strict_types=1);

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Очистка HTML из админского WYSIWYG перед сохранением / выводом.
 * strip_tags не удаляет опасные атрибуты — дополнительно чистим DOM.
 */
final class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'a', 'ul', 'ol', 'li', 'strong', 'b', 'em', 'i',
        'h2', 'h3', 'h4', 'span', 'div',
    ];

    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $allowedList = '<'.implode('><', self::ALLOWED_TAGS).'>';
        $stripped = strip_tags($html, $allowedList);

        if ($stripped === '' || ! class_exists(DOMDocument::class)) {
            return $stripped;
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrapped = '<?xml encoding="UTF-8"><div id="__sanitize_root">'.$stripped.'</div>';
        $loaded = $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if ($loaded === false) {
            return $stripped;
        }

        $root = $dom->getElementById('__sanitize_root');
        if (! $root instanceof DOMElement) {
            return $stripped;
        }

        self::sanitizeNode($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    private static function sanitizeNode(DOMNode $node): void
    {
        if (! $node->hasChildNodes()) {
            return;
        }

        /** @var list<DOMNode> $children */
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);
                if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    $child->parentNode?->removeChild($child);
                    continue;
                }

                self::sanitizeAttributes($child);
                self::sanitizeNode($child);
            }
        }
    }

    private static function sanitizeAttributes(DOMElement $el): void
    {
        $tag = strtolower($el->tagName);
        $allowed = match ($tag) {
            'a' => ['href', 'title', 'rel', 'target'],
            'span', 'div', 'p', 'h2', 'h3', 'h4', 'li', 'ul', 'ol', 'strong', 'b', 'em', 'i', 'br' => [],
            default => [],
        };

        /** @var list<string> $names */
        $names = [];
        if ($el->hasAttributes()) {
            foreach ($el->attributes as $attr) {
                $names[] = $attr->name;
            }
        }

        foreach ($names as $name) {
            $lower = strtolower($name);
            if (str_starts_with($lower, 'on') || ! in_array($lower, $allowed, true)) {
                $el->removeAttribute($name);
                continue;
            }

            if ($lower === 'href') {
                $href = trim($el->getAttribute('href'));
                if ($href === '' || preg_match('#^\s*(javascript|data|vbscript):#i', $href) === 1) {
                    $el->removeAttribute('href');
                    continue;
                }
                if (! preg_match('#^(https?:)?//|^/|^mailto:|^tel:|^\##i', $href)) {
                    $el->removeAttribute('href');
                }
            }

            if ($lower === 'target') {
                $target = $el->getAttribute('target');
                if ($target !== '_blank' && $target !== '_self') {
                    $el->removeAttribute('target');
                } elseif ($target === '_blank') {
                    $rel = $el->getAttribute('rel');
                    if (! str_contains($rel, 'noopener')) {
                        $el->setAttribute('rel', trim($rel.' noopener noreferrer'));
                    }
                }
            }
        }
    }
}
