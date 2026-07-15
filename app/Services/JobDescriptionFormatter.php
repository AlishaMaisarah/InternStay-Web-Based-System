<?php

namespace App\Services;

class JobDescriptionFormatter
{
    /**
     * Format plain text job description into structured HTML with headings and bulleted lists.
     *
     * @param string|null $description
     * @return array Contains 'summary_html', 'rest_html', and 'should_collapse'
     */
    public static function format($description)
    {
        if (empty($description)) {
            return [
                'summary_html' => '<p class="text-secondary" style="line-height: 1.6;">No description available.</p>',
                'rest_html' => '',
                'should_collapse' => false
            ];
        }

        // Insert line breaks before common headings
        $description = preg_replace(
            '/\b(Responsibilities|Key Responsibilities|Requirements|Qualifications|Benefits|Skills|What You Will Gain|What You Will Do|Website\s*&\s*Software Operations|QA\s*&\s*Testing|About You|Overview|Role|Duties)\b\s*:?/i',
            "\n$1\n",
            $description
        );

        // Split sentences after periods if followed by a capital letter
        $description = preg_replace(
            '/\.\s+(?=[A-Z])/',
            ".\n",
            $description
        );

        // Normalize newlines and split into lines
        $normalized = str_replace(["\r\n", "\r"], "\n", $description);
        $rawLines = explode("\n", $normalized);
        
        $lines = [];
        $seen = [];
        foreach ($rawLines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                continue;
            }
            
            // Strip duplicate lines to remove excessive duplicate content
            $lower = strtolower($trimmed);
            if (isset($seen[$lower]) && strlen($trimmed) > 30) {
                continue; 
            }
            $seen[$lower] = true;
            $lines[] = $trimmed;
        }

        // Section heading detection patterns (e.g. Responsibilities, Requirements, Qualifications, etc.)
        $headingsRegex = '/^(?:key\s+|your\s+|role\s+&?\s*|minimum\s+|preferred\s+|job\s+|candidate\s+|essential\s+)?(?:responsibilities|requirements|qualifications|benefits|skills|gains?|what\s+you\s+will\s+(?:gain|do)|what\s+you\'ll\s+(?:gain|do)|what\s+we\s+offer|perks|website\s+&?\s*software\s+operations|qa\s+&?\s*testing|about\s+the\s+role|role\s+description|overview|about\s+us|about\s+the\s+company|what\s+you\s+do|what\s+we\s+look\s+for|what\s+we\s+are\s+looking\s+for|requirements\s+&\s+skills|preferred\s+skills|what\s+is\s+in\s+store\s+for\s+you):?$/i';

        $structuredBlocks = [];
        $currentHeading = null;
        $currentList = [];
        $hasSeenHeadingOrList = false;

        foreach ($lines as $line) {
            // Check if line is a bullet item by prefix (remove the bullet marker character)
            $isBulletPrefixed = preg_match('/^(?:[•\*\-o▪–★✓✔\d]+\.?|\w\))\s*(.*)$/u', $line, $matches);
            
            // Special regex to match bullet-prefixed items without losing numbers if they are just normal text
            $bulletMatch = false;
            $cleanLine = $line;
            if (preg_match('/^(?:[•\*\-o▪–★✓✔]|\d+\.|\w\))\s*(.*)$/u', $line, $m)) {
                $bulletMatch = true;
                $cleanLine = trim($m[1]);
            }

            // Clean the line for heading check by stripping leading symbols/emojis
            // E.g. "🔧 Your Responsibilities" -> "Your Responsibilities"
            $cleanedForHeadingCheck = preg_replace('/^[^\p{L}\p{N}]+/u', '', $line);
            $cleanedForHeadingCheck = trim($cleanedForHeadingCheck);

            // Check if line matches a known heading keyword
            $isHeading = false;
            if (strlen($cleanedForHeadingCheck) < 65) {
                // If it ends with a colon and is short, it's almost certainly a heading (e.g. "Requirements:")
                if (
                    (
                        str_ends_with($cleanedForHeadingCheck, ':') ||
                        str_ends_with($cleanedForHeadingCheck, '：')
                    )
                    && strlen($cleanedForHeadingCheck) < 80
                ) {
                    $isHeading = true;
                } else {
                    $headingPatterns = [
                        $headingsRegex,
                        '/^(?:the\s+)?role$/i',
                        '/^(?:the\s+)?day-to-day(?:\s+activities)?$/i',
                        '/^about\s+you$/i',
                        '/^who\s+you\s+are$/i',
                        '/^what\'s\s+next(?:\s*\?)?$/i',
                        '/^what’s\s+next(?:\s*\?)?$/i',
                        '/^life\s+at\s+.+$/i',
                        '/^awarded\s+for$/i',
                        '/^top\s+reasons\s+to\s+join\s+us$/i',
                        '/^about\s+.+$/i'
                    ];
                    
                    foreach ($headingPatterns as $pattern) {
                        if (preg_match($pattern, $cleanedForHeadingCheck)) {
                            $isHeading = true;
                            break;
                        }
                    }
                }
            }

            if ($isHeading) {
                $hasSeenHeadingOrList = true;
                
                // Close previous list if any
                if (!empty($currentList)) {
                    $structuredBlocks[] = ['type' => 'list', 'items' => $currentList];
                    $currentList = [];
                }
                
                // Clean heading name (remove trailing colon or dashes if any)
                $headingText = rtrim($line, " \t\n\r\0\x0B:-•");
                $structuredBlocks[] = ['type' => 'heading', 'text' => $headingText];
                $currentHeading = $headingText;
            } elseif ($bulletMatch) {
                $hasSeenHeadingOrList = true;
                $currentList[] = $cleanLine;
            } else {
                // Plain line of text (no bullet prefix, not a heading)
                if (!$hasSeenHeadingOrList) {
                    // Part of the introductory summary
                    $structuredBlocks[] = ['type' => 'paragraph', 'text' => $line];
                } else {
                    // We are after some heading/list
                    if ($currentHeading !== null) {
                        // Check if current heading is paragraph-style
                        $paragraphHeadingsRegex = '/^(?:the\s+)?(?:role|overview|job\s+description|about\s+the\s+company|about\s+us|company\s+description|summary):?$/i';
                        $isParagraphStyle = preg_match($paragraphHeadingsRegex, trim(preg_replace('/^[^\p{L}\p{N}]+/u', '', $currentHeading)));
                        
                        if ($isParagraphStyle) {
                            if ($bulletMatch) {
                                $currentList[] = $cleanLine;
                            } else {
                                if (!empty($currentList)) {
                                    $structuredBlocks[] = ['type' => 'list', 'items' => $currentList];
                                    $currentList = [];
                                }
                                $structuredBlocks[] = ['type' => 'paragraph', 'text' => $line];
                            }
                        } else {
                            // List-style heading. Render as paragraph if it's very long, otherwise list item.
                            if (strlen($line) > 350) {
                                if (!empty($currentList)) {
                                    $structuredBlocks[] = ['type' => 'list', 'items' => $currentList];
                                    $currentList = [];
                                }
                                $structuredBlocks[] = ['type' => 'paragraph', 'text' => $line];
                            } else {
                                    // Convert short action sentences into bullet items
                                    if (
                                        strlen($line) < 180 &&
                                        preg_match(
                                            '/^(Assist|Support|Manage|Maintain|Develop|Design|Coordinate|Monitor|Perform|Prepare|Create|Handle|Conduct|Review|Work|Collaborate|Analyse|Analyze|Help|Implement|Test|Build|Participate|Identify)\b/i',
                                            $line
                                        )
                                    ) {
                                        $currentList[] = $line;
                                    } else {
                                        if (!empty($currentList)) {
                                            $structuredBlocks[] = [
                                                'type' => 'list',
                                                'items' => $currentList,
                                            ];
                                            $currentList = [];
                                        }

                                        $structuredBlocks[] = [
                                            'type' => 'paragraph',
                                            'text' => $line,
                                        ];
                                    }
                                }
                        }
                    } else {
                        // No active heading, but we've seen list items.
                        if (strlen($line) > 250) {
                            if (!empty($currentList)) {
                                $structuredBlocks[] = ['type' => 'list', 'items' => $currentList];
                                $currentList = [];
                            }
                            $structuredBlocks[] = ['type' => 'paragraph', 'text' => $line];
                        } else {
                            $currentList[] = $line;
                        }
                    }
                }
            }
        }

        // Close any remaining list at the end
        if (!empty($currentList)) {
            $structuredBlocks[] = ['type' => 'list', 'items' => $currentList];
        }

        // Split blocks at exactly 5 lines
        list($summaryBlocks, $restBlocks) = self::splitBlocks($structuredBlocks, 5);

        // Build the introductory summary HTML
        $summaryHtml = '';
        foreach ($summaryBlocks as $block) {
            $summaryHtml .= self::renderBlock($block);
        }

        // Build the rest HTML
        $restHtml = '';
        foreach ($restBlocks as $block) {
            $restHtml .= self::renderBlock($block);
        }

        return [
            'summary_html' => trim($summaryHtml),
            'rest_html' => trim($restHtml),
            'should_collapse' => !empty($restBlocks)
        ];
    }

    /**
     * Split blocks at exactly $maxLines limit.
     * Handles splitting of list blocks into two lists when needed.
     */
    private static function splitBlocks($blocks, $maxLines = 5)
    {
        $summaryBlocks = [];
        $restBlocks = [];
        $lineCount = 0;
        $splitDone = false;

        foreach ($blocks as $block) {
            if ($splitDone) {
                $restBlocks[] = $block;
                continue;
            }

            if ($block['type'] === 'heading' || $block['type'] === 'paragraph') {
                if ($lineCount < $maxLines) {
                    $summaryBlocks[] = $block;
                    $lineCount++;
                } else {
                    $restBlocks[] = $block;
                    $splitDone = true;
                }
            } elseif ($block['type'] === 'list') {
                $itemsCount = count($block['items']);
                if ($lineCount + $itemsCount <= $maxLines) {
                    $summaryBlocks[] = $block;
                    $lineCount += $itemsCount;
                } else {
                    // We need to split the list
                    $allowedItems = $maxLines - $lineCount;
                    if ($allowedItems > 0) {
                        $summaryBlocks[] = [
                            'type' => 'list',
                            'items' => array_slice($block['items'], 0, $allowedItems)
                        ];
                        $restBlocks[] = [
                            'type' => 'list',
                            'items' => array_slice($block['items'], $allowedItems)
                        ];
                    } else {
                        $restBlocks[] = $block;
                    }
                    $splitDone = true;
                }
            }
        }

        return [$summaryBlocks, $restBlocks];
    }

    /**
     * Render block structure into HTML.
     */
    private static function renderBlock($block)
    {
        switch ($block['type']) {
            case 'heading':
                return '<h5 class="fw-bold text-dark mt-4 mb-2" style="font-size: 1.05rem;">' . e($block['text']) . '</h5>';
            case 'paragraph':
                return '<p class="text-secondary mt-2 mb-2" style="line-height: 1.6; white-space: pre-line;">' . e($block['text']) . '</p>';
            case 'list':
                $html = '<ul class="text-secondary ps-4 mt-2 mb-3" style="line-height: 1.7; list-style-type: disc;">';
                foreach ($block['items'] as $item) {
                    $html .= '<li class="mb-1.5">' . e($item) . '</li>';
                }
                $html .= '</ul>';
                return $html;
            default:
                return '';
        }
    }
}
