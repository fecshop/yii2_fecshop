$db->createCommand("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES (<?= $adminUrlKey ?>, 1)")->execute();
$lastInsertId = $db->getLastInsertID() ;
$db->createCommand("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", <?= time() ?>, <?= time() ?>)")->execute();

