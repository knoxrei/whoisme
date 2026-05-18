<?php

namespace App\Services;

class SnippetExtractionService
{
    /**
     * Extracts a context-aware snippet from content and highlights query terms.
     */
    public function extract(string $content, ?string $query, int $maxLength = 180): string
    {
        $cleanContent = strip_tags($content);
        $escapedContent = htmlspecialchars($cleanContent, ENT_QUOTES, 'UTF-8');

        if (!$query) {
            return mb_strimwidth($escapedContent, 0, $maxLength, '...');
        }

        // Parse search query into individual terms
        $terms = $this->extractTerms($query);
        if (empty($terms)) {
            return mb_strimwidth($escapedContent, 0, $maxLength, '...');
        }

        // Find the best term occurrence location to center our snippet
        $bestPos = 0;
        $bestScore = 0;

        foreach ($terms as $term) {
            $escapedTerm = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
            $pos = mb_stripos($escapedContent, $escapedTerm);
            if ($pos !== false) {
                $score = 1;
                // Check if other terms are close by to score the window higher
                foreach ($terms as $otherTerm) {
                    if ($otherTerm === $term) continue;
                    $otherEscaped = htmlspecialchars($otherTerm, ENT_QUOTES, 'UTF-8');
                    $otherPos = mb_stripos($escapedContent, $otherEscaped);
                    if ($otherPos !== false && abs($otherPos - $pos) < $maxLength) {
                        $score += 2;
                    }
                }

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestPos = $pos;
                }
            }
        }

        // Calculate snippet window boundaries
        $start = max(0, $bestPos - intval($maxLength / 2));
        $snippet = mb_substr($escapedContent, $start, $maxLength);

        // Adjust boundaries so we don't start/end in the middle of a word if possible
        if ($start > 0) {
            $spacePos = mb_strpos($snippet, ' ');
            if ($spacePos !== false && $spacePos < 20) {
                $snippet = mb_substr($snippet, $spacePos + 1);
            }
            $snippet = '...' . $snippet;
        }

        if (mb_strlen($escapedContent) > ($start + $maxLength)) {
            $lastSpace = mb_strrpos($snippet, ' ');
            if ($lastSpace !== false && $lastSpace > (mb_strlen($snippet) - 20)) {
                $snippet = mb_substr($snippet, 0, $lastSpace);
            }
            $snippet .= '...';
        }

        // Safely highlight the terms inside our snippet
        return $this->highlight($snippet, $terms);
    }

    /**
     * Parse the raw query to extract separate terms (including phrases).
     */
    private function extractTerms(string $query): array
    {
        // Extract phrases inside quotes
        preg_match_all('/"([^"]+)"/', $query, $matches);
        $phrases = $matches[1];
        
        // Remove quotes and split remaining string by spaces
        $cleanQuery = preg_replace('/"([^"]+)"/', '', $query);
        $words = preg_split('/\s+/', trim($cleanQuery), -1, PREG_SPLIT_NO_EMPTY);

        // Filter out short words and stop words
        $stopWords = ['and', 'or', 'not', 'the', 'for', 'with', 'this', 'that', 'you', 'was', 'are', 'but'];
        $words = array_filter($words, function ($word) use ($stopWords) {
            return mb_strlen($word) > 2 && !in_array(mb_strtolower($word), $stopWords);
        });

        return array_unique(array_merge($phrases, $words));
    }

    /**
     * Wraps matched search terms in a custom, clean visual highlight tag.
     */
    private function highlight(string $text, array $terms): string
    {
        if (empty($terms)) {
            return $text;
        }

        // Sort terms from longest to shortest to avoid nested highlight replacements
        usort($terms, function ($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });

        foreach ($terms as $term) {
            $escapedTerm = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
            // Using a unique temporary placeholder to prevent nested tagging
            $placeholder = '##MARK_START##' . $escapedTerm . '##MARK_END##';
            $text = preg_replace('/(' . preg_quote($escapedTerm, '/') . ')/ui', $placeholder, $text);
        }

        // Resolve placeholders into stylish, lightweight, pure-HTML tags
        $text = str_replace(
            ['##MARK_START##', '##MARK_END##'],
            [
                '<mark class="bg-red-600/20 text-red-500 border border-red-900/30 px-1 rounded-sm">',
                '</mark>'
            ],
            $text
        );

        return $text;
    }
}
