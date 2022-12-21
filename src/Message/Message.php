<?php
declare(strict_types=1);

namespace ErickSkrauch\Fcm\Message;

final class Message {

    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';

    /**
     * @var array{
     *     notification?: \ErickSkrauch\Fcm\Message\Notification,
     *     restricted_package_name?: string,
     *     collapse_key?: string,
     *     priority?: string,
     *     time_to_live?: int,
     *     mutable_content?: true,
     *     content_available?: true,
     *     image?: string,
     *     data?: array<string, string|int>,
     * }
     */
    private array $data = [];

    public function setNotification(Notification $notification): self {
        $this->data['notification'] = $notification;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/http-server-ref#ttl (see section below since there is no anchor)
     */
    public function setRestrictedPackageName(string $packageName): self {
        $this->data['restricted_package_name'] = $packageName;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/concept-options#collapsible_and_non-collapsible_messages
     */
    public function setCollapseKey(string $collapseKey): self {
        $this->data['collapse_key'] = $collapseKey;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/concept-options#setting-the-priority-of-a-message
     * @param self::PRIORITY_NORMAL|self::PRIORITY_HIGH $priority
     */
    public function setPriority(string $priority): self {
        $this->data['priority'] = $priority;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/concept-options#ttl
     */
    public function setTimeToLive(int $ttl): self {
        $this->data['time_to_live'] = $ttl;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/http-server-ref#mutable_content
     */
    public function setMutableContent(): self {
        $this->data['mutable_content'] = true;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/http-server-ref#content_available
     */
    public function setContentAvailable(): self {
        $this->data['content_available'] = true;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/android/send-image
     */
    public function setImage(string $imageUrl): self {
        $this->data['image'] = $imageUrl;
        return $this;
    }

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/http-server-ref#data
     * @param array<string, string|int> $data
     */
    public function setData(array $data): self {
        $this->data['data'] = $data;
        return $this;
    }

    /**
     * @return array{
     *     notification?: \ErickSkrauch\Fcm\Message\Notification,
     *     restricted_package_name?: string,
     *     collapse_key?: string,
     *     priority?: string,
     *     time_to_live?: int,
     *     mutable_content?: true,
     *     content_available?: true,
     *     image?: string,
     *     data?: array<string, string|int>,
     * }
     */
    public function getPayload(): array {
        return $this->data;
    }

}
