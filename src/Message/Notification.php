<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Message;

use InvalidArgumentException;
use JsonSerializable;

/**
 * @link https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support
 */
final class Notification implements JsonSerializable {

    /**
     * @var array{
     *     title?: string,
     *     subtitle?: string,
     *     body?: string,
     *     badge?: int,
     *     icon?: string,
     *     color?: string,
     *     click_action?: string,
     *     tag?: string,
     *     sound?: string,
     *     title_loc_key?: string,
     *     title_loc_args?: string,
     *     body_loc_key?: string,
     *     body_loc_args?: string,
     *     android_channel_id?: string,
     * }
     */
    private array $data = [];

    public function setTitle(string $title): self {
        if (isset($this->data['title_loc_key'])) {
            throw new InvalidArgumentException('You cannot use "title" and "title_loc_key" at the same time');
        }

        $this->data['title'] = $title;

        return $this;
    }

    /**
     * iOS only
     */
    public function setSubtitle(string $subtitle): self {
        $this->data['subtitle'] = $subtitle;
        return $this;
    }

    public function setBody(string $body): self {
        if (isset($this->data['body_loc_key'])) {
            throw new InvalidArgumentException('You cannot use "body" and "body_loc_key" at the same time');
        }

        $this->data['body'] = $body;

        return $this;
    }

    /**
     * iOS only
     */
    public function setBadge(int $badge): self {
        $this->data['badge'] = $badge;
        return $this;
    }

    /**
     * Android only
     */
    public function setIcon(string $icon): self {
        $this->data['icon'] = $icon;
        return $this;
    }

    /**
     * Android only
     */
    public function setColor(string $color): self {
        $this->data['color'] = $color;
        return $this;
    }

    public function setClickAction(string $actionName): self {
        $this->data['click_action'] = $actionName;
        return $this;
    }

    /**
     * Android only
     */
    public function setTag(string $tag): self {
        $this->data['tag'] = $tag;
        return $this;
    }

    public function setSound(string $sound): self {
        $this->data['sound'] = $sound;
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @param array<string|int>|null $args
     * @return self
     */
    public function setTitleLocKey(string $titleLocKey, ?array $args = null): self {
        if (isset($this->data['title'])) {
            throw new InvalidArgumentException('You cannot use "title" and "title_loc_key" at the same time');
        }

        $this->data['title_loc_key'] = $titleLocKey;
        if (!empty($args)) {
            $this->data['title_loc_args'] = json_encode($args, JSON_THROW_ON_ERROR);
        }

        return $this;
    }

    /**
     * @param string $bodyLocKey
     * @param array<string|int>|null $args
     * @return self
     */
    public function setBodyLocKey(string $bodyLocKey, ?array $args = null): self {
        if (isset($this->data['body'])) {
            throw new InvalidArgumentException('You cannot use "body" and "body_loc_key" at the same time');
        }

        $this->data['body_loc_key'] = $bodyLocKey;
        if (!empty($args)) {
            $this->data['body_loc_args'] = json_encode($args, JSON_THROW_ON_ERROR);
        }

        return $this;
    }

    /**
     * Android only
     */
    public function setAndroidChannelId(string $channelId): self {
        $this->data['android_channel_id'] = $channelId;
        return $this;
    }

    /**
     * @return array{
     *     title?: string,
     *     subtitle?: string,
     *     body?: string,
     *     badge?: int,
     *     icon?: string,
     *     color?: string,
     *     click_action?: string,
     *     tag?: string,
     *     sound?: string,
     *     title_loc_key?: string,
     *     title_loc_args?: string,
     *     body_loc_key?: string,
     *     body_loc_args?: string,
     *     android_channel_id?: string,
     * }
     */
    public function jsonSerialize(): array {
        return $this->data;
    }

}
