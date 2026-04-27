<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function gg_seo_meta_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function gg_seo_plain_text($value)
{
    $value = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = preg_replace('#<script\b[^>]*>.*?</script>#is', ' ', $value);
    $value = preg_replace('#<style\b[^>]*>.*?</style>#is', ' ', $value);
    $value = strip_tags($value);
    $value = preg_replace('/\[[^\]]+\]/u', ' ', $value);
    $value = preg_replace('/\s+/u', ' ', $value);

    return trim($value);
}

function gg_seo_absolute_url($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }

    if (strpos($url, '//') === 0) {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https:' : 'http:') . $url;
    }

    if (strpos($url, '/') === 0) {
        return rtrim(G5_URL, '/') . $url;
    }

    return rtrim(G5_URL, '/') . '/' . ltrim($url, '/');
}

function gg_seo_board_canonical_url($bo_table, $wr_id, $write)
{
    if (function_exists('get_pretty_url')) {
        return html_entity_decode(get_pretty_url($bo_table, $wr_id), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    return G5_BBS_URL . '/board.php?bo_table=' . rawurlencode($bo_table) . '&wr_id=' . (int) $wr_id;
}

function gg_seo_board_first_image($bo_table, $view)
{
    global $config;

    $image_extensions = isset($config['cf_image_extension']) && $config['cf_image_extension']
        ? $config['cf_image_extension']
        : 'gif|jpg|jpeg|png|webp|bmp';

    if (isset($view['file']['count']) && (int) $view['file']['count'] > 0) {
        for ($i = 0; $i < (int) $view['file']['count']; $i++) {
            if (empty($view['file'][$i]['file'])) {
                continue;
            }

            $file = $view['file'][$i]['file'];
            $is_image = !empty($view['file'][$i]['image_type']) || preg_match('/\.(' . $image_extensions . ')$/i', $file);
            if ($is_image) {
                return G5_DATA_URL . '/file/' . $bo_table . '/' . rawurlencode($file);
            }
        }
    }

    $content = isset($view['wr_content']) ? $view['wr_content'] : '';
    if (function_exists('get_editor_image')) {
        $matches = get_editor_image($content);
        if (isset($matches[1]) && is_array($matches[1])) {
            foreach ($matches[1] as $img) {
                if (preg_match('/src=["\']?([^>"\']+)/i', $img, $m) && !empty($m[1])) {
                    return gg_seo_absolute_url($m[1]);
                }
            }
        }
    }

    if (preg_match('/<img[^>]+src=["\']?([^>"\']+)/i', $content, $m) && !empty($m[1])) {
        return gg_seo_absolute_url($m[1]);
    }

    return '';
}

function gg_seo_board_keywords($title, $category, $content)
{
    $source = trim($title . ' ' . $category . ' ' . $content);
    $keywords = array();

    foreach (array($title, $category) as $priority_word) {
        $priority_word = trim($priority_word);
        if ($priority_word !== '') {
            $keywords[$priority_word] = true;
        }
    }

    if (preg_match_all('/[가-힣a-zA-Z0-9][가-힣a-zA-Z0-9+#_-]{1,}/u', $source, $matches)) {
        $stop_words = array(
            '그리고' => true, '그러나' => true, '입니다' => true, '합니다' => true,
            '있는' => true, '없는' => true, '대한' => true, '관련' => true,
            'this' => true, 'that' => true, 'with' => true, 'from' => true,
        );

        foreach ($matches[0] as $word) {
            $word = trim($word);
            $key = function_exists('mb_strtolower') ? mb_strtolower($word, 'UTF-8') : strtolower($word);
            if ($word === '' || isset($stop_words[$key]) || preg_match('/^\d+$/', $word)) {
                continue;
            }
            $keywords[$word] = true;
            if (count($keywords) >= 20) {
                break;
            }
        }
    }

    return implode(', ', array_slice(array_keys($keywords), 0, 20));
}

function gg_get_board_view_seo_meta()
{
    global $bo_table, $wr_id, $write, $board, $board_skin_path;

    if (empty($bo_table) || empty($wr_id) || empty($write) || empty($write['wr_id'])) {
        return null;
    }

    $view = function_exists('get_view') ? get_view($write, $board, $board_skin_path) : $write;
    $title = gg_seo_plain_text(isset($view['wr_subject']) ? $view['wr_subject'] : '');
    if ($title === '') {
        return null;
    }

    $category = gg_seo_plain_text(isset($view['ca_name']) ? $view['ca_name'] : (isset($write['ca_name']) ? $write['ca_name'] : ''));
    $content = gg_seo_plain_text(isset($view['wr_content']) ? $view['wr_content'] : (isset($write['wr_content']) ? $write['wr_content'] : ''));
    $description = function_exists('cut_str') ? cut_str($content, 155, '') : substr($content, 0, 155);
    $description = trim($description) !== '' ? trim($description) : $title;
    $canonical = gg_seo_board_canonical_url($bo_table, $wr_id, $write);
    $image = gg_seo_board_first_image($bo_table, $view);

    return array(
        'title' => $title,
        'description' => $description,
        'keywords' => gg_seo_board_keywords($title, $category, $content),
        'image' => $image,
        'canonical' => $canonical,
    );
}

function gg_render_board_view_seo_meta($meta)
{
    if (empty($meta) || empty($meta['title'])) {
        return '';
    }

    $title = gg_seo_meta_escape($meta['title']);
    $description = gg_seo_meta_escape($meta['description']);
    $keywords = gg_seo_meta_escape($meta['keywords']);
    $canonical = gg_seo_meta_escape($meta['canonical']);
    $image = !empty($meta['image']) ? gg_seo_meta_escape($meta['image']) : '';

    $html = array();
    $html[] = '<meta name="title" content="' . $title . '" />';
    $html[] = '<meta name="description" content="' . $description . '" />';
    if ($keywords !== '') {
        $html[] = '<meta name="keywords" content="' . $keywords . '" />';
    }
    $html[] = '<link rel="canonical" href="' . $canonical . '" />';
    $html[] = '<meta property="og:type" content="article" />';
    $html[] = '<meta property="og:url" content="' . $canonical . '" />';
    $html[] = '<meta property="og:title" content="' . $title . '" />';
    $html[] = '<meta property="og:description" content="' . $description . '" />';
    if ($image !== '') {
        $html[] = '<meta property="og:image" content="' . $image . '" />';
    }
    $html[] = '<meta name="twitter:card" content="' . ($image !== '' ? 'summary_large_image' : 'summary') . '" />';
    $html[] = '<meta name="twitter:title" content="' . $title . '" />';
    $html[] = '<meta name="twitter:description" content="' . $description . '" />';
    if ($image !== '') {
        $html[] = '<meta name="twitter:image" content="' . $image . '" />';
    }

    return implode(PHP_EOL, $html) . PHP_EOL;
}

function gg_filter_duplicate_board_seo_meta($html)
{
    if (trim((string) $html) === '') {
        return '';
    }

    $patterns = array(
        '#<meta\s+[^>]*name=["\']?(title|description|keywords|twitter:card|twitter:title|twitter:description|twitter:image)["\']?[^>]*>\s*#iu',
        '#<meta\s+[^>]*property=["\']?(og:type|og:url|og:title|og:description|og:image)["\']?[^>]*>\s*#iu',
        '#<link\s+[^>]*rel=["\']?canonical["\']?[^>]*>\s*#iu',
    );

    return trim(preg_replace($patterns, '', (string) $html));
}
