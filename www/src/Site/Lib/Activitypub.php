<?php
namespace Site\Lib;

use Site\Lib\Database;
use Sns\Model\Users;

class ActivityPub extends Database {
  private $domain;
  private $actor;

  public function __construct(string $domain = null) {
    parent::__construct();
    $this->domain = $domain ?? $_SERVER['SERVER_NAME'];
  }

  /**
   * ActivityPubアクタープロフィールを受け取る
   *
   * @param string $username ローカルユーザー
   * @return array アクターオブジェクト
   */
  public function getActor(string $username): array {
    $user = new Users();
    $u = $user->getUser($username);
    $p = $user->getProfileOfUser($u->id);
    $a = $user->getAvatarOfUser($u->id);

    $actor = [
      '@context' => [
        'https://www.w3.org/ns/activitystreams',
        'https://w3id.org/security/v1',
      ],
      'id' => "https://{$this->domain}/users/{$username}",
      'name' => $p->fullname,
      'summary' => $p->bio,
      'icon' => [
        'type' => 'Image',
        'mediaType' => $a->mediatype,
        'url' => "https://{$this->domain}/static/avatar/{$a->filename}",
      ],
      'type' => 'Person',
      'preferredUsername' => $username,
      'inbox' => "https://{$this->domain}/users/{$username}/inbox",
      'outbox' => "https://{$this->domain}/users/{$username}/outbox",
      'followers' => "https://{$this->domain}/users/{$username}/followers",
      'following' => "https://{$this->domain}/users/{$username}/following",
      'publicKey' => [
        'id' => "https://{$this->domain}/users/{$username}#main-key",
        'owner' => "https://{$this->domain}/users/{$username}",
        'publicKeyPem' => $user->getRsaFromUser($u->id)->public_key,
      ],
    ];

    return $actor;
  }
}
?>
