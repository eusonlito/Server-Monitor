<?php declare(strict_types=1);

namespace App\Services\Html\Traits;

trait Custom
{
    /**
     * @param string $name
     * @param string $class = ''
     *
     * @return string
     */
    public static function icon(string $name, string $class = ''): string
    {
        return '<svg class="feather '.$class.'"><use xlink:href="'.static::asset('build/images/feather-sprite.svg').'#'.$name.'" /></svg>';
    }

    /**
     * @param float $percent
     * @param string $class = 'h-3'
     *
     * @return string
     */
    public static function progressbar(float $percent, string $class = 'h-3'): string
    {
        if ($percent >= 90) {
            $color = '#F15B38';
        } elseif ($percent >= 70) {
            $color = '#EDBE38';
        } else {
            $color = '#1E3A8A';
        }

        $html = trim('
            <div class="w-100 bg-secondary rounded overflow-hidden flex-grow-1 :class">
                <div role="progressbar" aria-valuenow=":percent" aria-valuemin="0" aria-valuemax="100" class="h-100 rounded d-flex align-content-center align-self-center" style="background-color: :color; width: :percent%"></div>
            </div>
        ');

        return strtr($html, [
            ':class' => $class,
            ':percent' => $percent,
            ':color' => $color,
        ]);
    }

    /**
     * @param mixed $status
     *
     * @return string
     */
    public static function status(mixed $status): string
    {
        $status = boolval($status);

        if ($status) {
            $color = 'text-success';
            $icon = 'check-square';
        } else {
            $color = 'text-danger';
            $icon = 'square';
        }

        return '<span class="svg-icon svg-icon-2 '.$color.'">'
            .static::icon($icon, 'w-4 h-4')
            .'</span>';
    }

    /**
     * @param string $path
     * @param string $class = ''
     * @param bool $tags = false
     *
     * @return string
     */
    public static function svg(string $path, string $class = '', bool $tags = false): string
    {
        $html = $tags ? '<span class="svg-icon svg-icon-2 '.$class.'">' : '';
        $html .= str_replace('class=""', 'class="'.$class.'"', static::inline($path));
        $html .= $tags ? '</span>' : '';

        return $html;
    }

    /**
     * @param string $name
     * @param string $title
     * @param string $class = ''
     *
     * @return string
     */
    public static function thOrder(string $name, string $title, string $class = ''): string
    {
        $column = request()->input('order_column');
        $mode = (request()->input('order_mode') === 'ASC') ? 'ASC' : 'DESC';

        $link = static fn ($mode) => '<a class="text-muted" href="?'.helper()->query([
            'order_column' => $name,
            'order_mode' => $mode,
        ]).'">'.$title.'</a>';

        if ($column !== $name) {
            return '<th class="'.$class.'">'.$link($mode).'</th>';
        }

        if ($mode === 'DESC') {
            return '<th class="'.$class.' table-sort-desc">'.$link('ASC').'</th>';
        }

        return '<th class="'.$class.' table-sort-asc">'.$link('DESC').'</th>';
    }

    /**
     * @param string $color
     * @param string $text
     *
     * @return string
     */
    public static function trStatus(string $color, string $text): string
    {
        switch ($color) {
            case '#1C8A4A':
                $class = 'alert-success';
                break;

            case '#0883ff':
            case '#08c1ff':
                $class = 'alert-primary';
                break;

            case '#ff0000':
            case '#f48236':
                $class = 'alert-danger';
                break;

            default:
                $class = 'alert-info';
                break;
        }

        return '<div class="alert '.$class.' d-flex align-items-center mb-0">'
            .'<span>'.$text.'</span>'
            .'</div>';
    }

    /**
     * @param ?array $data
     * @param string $delimiter = ' - '
     *
     * @return string
     */
    public static function arrayAsText(?array $data, string $delimiter = ' - '): string
    {
        return implode($delimiter, array_map(static fn ($key, $value) => ucfirst($key).': '.static::valueToString($value), array_keys($data), $data));
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public static function valueToString(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return substr(json_encode($value), 0, 20).'...';
        }

        return strval($value);
    }
}
