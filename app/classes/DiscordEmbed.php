<?php

class DiscordEmbed
{
    # DiscordEmbed-PHP
    # github.com/renzbobz
    # 3/18/21


    public function __toString()
    {
        return $this->toJSON();
    }

    public function toArray()
    {
        return (array) $this;
    }

    public function toJSON()
    {
        return json_encode($this, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function getBaseURL()
    {
        $scheme = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https" : "http";
        $host = $_SERVER["HTTP_HOST"];
        $url = $scheme."://".$host;
        return $url;
    }

    private function resolveColor($color)
    {
        if ($color) {
            if (is_string($color)) {
                if ($color == "RANDOM") {
                    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                }
                if (preg_match("/,/", $color)) {
                    $color = sprintf("#%02x%02x%02x", ...explode(",", $color));
                }
                $color = hexdec($color);
            }
        }
        return $color;
    }

    private function resolveURL($url)
    {
        if (!preg_match("/(http|https)\:\/\//", $url)) {
            $self = $_SERVER["PHP_SELF"];
            $selfDir = dirname($self);
            $selfDirArr = explode("/", $selfDir);
            $filePath = realpath($url);
            $fpArr = explode("/", $filePath);
            $fpArrLength = count($fpArr);
            foreach ($fpArr as $indx => $val) {
                if (!$val) {
                    continue;
                }
                if (in_array($val, $selfDirArr)) {
                    array_splice($fpArr, 0, $indx);
                    $url = implode("/", $fpArr);
                    break;
                } else {
                    if ($fpArrLength - 1 == $indx) {
                        $url = $val;
                    }
                }
            }
            $url = $this->getBaseURL()."/".$url;
        }
        return $url;
    }

    # TITLE

    public function setTitle($title, $url='')
    {
        $this->title = $title;
        if ($url) {
            $this->setURL($url);
        }
        return $this;
    }
    public function appendTitle($title)
    {
        $this->title = $this->title.$title;
        return $this;
    }
    public function prependTitle($title)
    {
        $this->title = $title.$this->title;
        return $this;
    }

    # URL

    public function setURL($url='')
    {
        $this->url = $url ? $this->resolveURL($url) : $this->getBaseURL();
        return $this;
    }

    # DESCRIPTION

    public function setDescription($desc)
    {
        $this->description = $desc;
        return $this;
    }
    public function appendDescription($desc)
    {
        $this->description = $this->description.$desc;
        return $this;
    }
    public function prependDescription($desc)
    {
        $this->description = $desc.$this->description;
        return $this;
    }

    # COLOR

    public function setColor($color=0)
    {
        $this->color = $this->resolveColor($color);
        return $this;
    }

    # TIMESTAMP

    public function setTimestamp($timestamp=0)
    {
        if (!$timestamp) {
            $timestamp = date('c');
        }
        $this->timestamp = $timestamp;
        return $this;
    }

    # AUTHOR

    public function setAuthor($name, $url='', $icon='')
    {
        $this->author = [
      'name' => $name,
      'url' => isset($url) && empty($url) ? $this->getBaseURL() : $this->resolveURL($url),
      'icon_url' => $icon ? $this->resolveURL($icon) : $icon
    ];
        return $this;
    }

    # THUMBNAIL

    public function setThumbnail($url, $height=0, $width=0)
    {
        $this->thumbnail = [
      'url' => $this->resolveURL($url),
      'height' => $height,
      'width' => $width
    ];
        return $this;
    }

    # IMAGE

    public function setImage($url, $height=0, $width=0)
    {
        $this->image = [
      'url' => $this->resolveURL($url),
      'height' => $height,
      'width' => $width
    ];
        return $this;
    }

    # FOOTER

    public function setFooter($text, $icon='')
    {
        $this->footer = [
      'text' => $text,
      'icon_url' => $icon ? $this->resolveURL($icon) : $icon
    ];
        return $this;
    }

    # FIELDS

    public function addField($name, $val, $inline=false, $index=null)
    {
        $field = [$name, $val, $inline];
        if (isset($index)) {
            $this->spliceFields($index, 0, $field);
        } else {
            $this->fields[] = $this->formatField(...$field);
        }
        return $this;
    }

    private function formatField($name, $val, $inline=false)
    {
        return [
      'name' => $name,
      'value' => $val,
      'inline' => $inline
    ];
    }

    public function addFields(...$fields)
    {
        foreach ($fields as $field) {
            if (empty($field)) {
                continue;
            }
            $this->addField(...$field);
        }
        return $this;
    }

    public function spliceFields($index, $deleteCount=0, ...$fields)
    {
        if (!empty($fields)) {
            $fields = array_map(function ($field) {
                return $this->formatField(...$field);
            }, $fields);
        }
        array_splice($this->fields, $index, $deleteCount, $fields);
        return $this;
    }
}
