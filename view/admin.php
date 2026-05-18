<?php

require_once "../config/bootstrap.php";
require_once "../controller/AdminController.php";

?>

<link rel="stylesheet" href="/mindgrow/assets/css/style.css">

<div class="admin-body">
<div class="admin-layout">

    <div class="sidebar">
        <h2>MindGrow</h2>
        <a href="#users">👤 utilisateurs</a>
        <a href="#organizations">🏢 Organizations</a>
        <a href="#certifications">🎓 Certifications</a>
        <a href="#courses">📚 Cours</a>
        <a href="#admin-logs">📜 Admin Logs</a>
        

        <a href="../view/home.php">⬅ Accueil</a>
        <a href="../public/logout.php" class="logout">
            déconnexion
        </a>
    </div>

    <div class="admin-content">

        <div class="topbar">
            <h3> Tableau de bord administrateur</h3>
            <div>👋 <?= $_SESSION['user'] ?></div>
   
        </div>

        <div class="stats">

            <div class="stat-card">
                <h4>utilisateurs</h4>
                <p><?= $usersCount ?></p>
            </div>

            <div class="stat-card">
                <h4>Organizations</h4>
                <p><?= $orgsCount ?></p>
            </div>

            <div class="stat-card">
                <h4>Cours</h4>
                <p><?= $coursesCount ?></p>
            </div>

            <div class="stat-card">
                <h4>Certifications</h4>
                <p><?= $certsCount ?></p>
            </div>

        </div>

        <section id="users" class="section">
            <h2>👤 utilisateurs</h2>

            <div class="card-box">

            <?php foreach($users as $u): ?>

                <p>
                    <?= $u['name'] ?> - 
                    <strong><?= $u['role'] ?></strong>

                    <a href="../controller/RoleController.php?id=<?= $u['id'] ?>&role=admin">👑 Admin</a>
                    <a href="../controller/RoleController.php?id=<?= $u['id'] ?>&role=user">👤 User</a>
                            <a href="../controller/UserController.php?delete=<?= $u['id'] ?>"
           onclick="return confirm('Supprimer cet utilisateur ?')"
           style="color:red;">
           ❌ supprimer
        </a>
                </p>

            <?php endforeach; ?>

            </div>
        </section>
        <section id="organizations" class="section">

            <h2>🏢 Organizations</h2>

            <form method="POST"
      action="../controller/OrganizationController.php"
      enctype="multipart/form-data">

    <input type="text"
           name="name"
           placeholder="Organization name"
           value="<?= $editOrg['name'] ?? '' ?>"
           required><br><br>

    <input type="file" name="image"><br><br>

    <?php if ($editOrg): ?>
        <input type="hidden" name="id" value="<?= $editOrg['id'] ?>">

        <button name="update">💾 mise à jour Organization</button>
    <?php else: ?>
        <button name="add">➕ ajouter Organization</button>
    <?php endif; ?>

</form>
            <hr>

            <div class="grid">

            <?php foreach($orgs as $o): ?>

                <?php
                $stmt = $conn->prepare("SELECT * FROM certifications WHERE org_id=?");
                $stmt->execute([$o['id']]);
                $certs = $stmt->fetchAll();
                ?>

                <div class="card">

                    <img src="../uploads/images/<?= $o['image'] ?>">

                    <h3><?= $o['name'] ?></h3>

                    <ul>
                        <?php foreach($certs as $c): ?>
                            <li>🎓 <?= $c['name'] ?></li>
                        <?php endforeach; ?>
                    </ul>

<a href="../controller/OrganizationController.php?edit=<?= $o['id'] ?>">
    ✏️ modifier
</a><br>

<a href="../controller/OrganizationController.php?delete=<?= $o['id'] ?>"
   onclick="return confirm('Delete ?')">
    ❌ supprimer
</a>

                </div>

            <?php endforeach; ?>

            </div>

        </section>
<section class="section" id="certifications">

    <h2>🎓 Certifications</h2>

    <?php
    $certList = $conn->query("SELECT * FROM certifications")->fetchAll();
    ?>

    <form method="POST" action="../controller/CertificationController.php">

        <select name="id" id="certSelect">

            <option value="">Select certification (for edit/delete)</option>

            <?php foreach($certList as $c): ?>
                <option value="<?= $c['id'] ?>">
                    <?= $c['name'] ?>
                </option>
            <?php endforeach; ?>

        </select><br><br>

        <input type="text"
               name="name"
               id="nameField"
               placeholder="Certification name"><br><br>

        <select name="org_id" id="orgField">

            <option value="">Select organization</option>

            <?php foreach($orgs as $o): ?>
                <option value="<?= $o['id'] ?>">
                    <?= $o['name'] ?>
                </option>
            <?php endforeach; ?>

        </select><br><br>

        <button type="submit" name="add" onclick="enableAdd()">➕ ajouter</button>
        <button type="submit" name="update" onclick="enableEdit()">✏️ modifier</button>
        <button type="submit" name="delete" onclick="enableDelete()">🗑 supprimer</button>

    </form>

</section>

        <section id="courses" class="section">
            <h2>📚 Cours</h2>

            <div class="card-box">

                <form method="POST"
                      action="../controller/CourseController.php"
                      enctype="multipart/form-data">

                    <input type="text"
                           name="title"
                           placeholder="Title"
                           value="<?= $editCourse['title'] ?? '' ?>"
                           required><br><br>

                    <textarea name="content"
                              placeholder="Content"><?= $editCourse['content'] ?? '' ?></textarea><br><br>

                    <select name="cert_id" required>
                        <option value="">Select certification</option>

                        <?php
                        $allCerts = $conn->query("SELECT * FROM certifications")->fetchAll();
                        foreach ($allCerts as $cert):
                        ?>
                            <option value="<?= $cert['id'] ?>"
                                <?= isset($editCourse['cert_id']) && $editCourse['cert_id'] == $cert['id'] ? 'selected' : '' ?>>
                                <?= $cert['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <br><br>

                    <input type="file" name="file"><br><br>

                    <?php if ($editCourse): ?>
                        <input type="hidden" name="id" value="<?= $editCourse['id'] ?>">
                        <button name="update">💾 mise à jour</button>
                    <?php else: ?>
                        <button name="add">➕ ajouter Cours</button>
                    <?php endif; ?>

                </form>

                <hr>

                <?php
                $allCerts = $conn->query("SELECT * FROM certifications")->fetchAll();
                ?>

                <div class="accordion-container">

                <?php foreach($allCerts as $cert): ?>

                    <?php
                    $stmt = $conn->prepare("SELECT * FROM courses WHERE cert_id=?");
                    $stmt->execute([$cert['id']]);
                    $certCourses = $stmt->fetchAll();
                    ?>

                    <div class="accordion-item">

                        <div class="accordion-header"
                             onclick="toggleAccordion('cert<?= $cert['id'] ?>')">
                            📁 <?= $cert['name'] ?>
                        </div>

                        <div class="accordion-body" id="cert<?= $cert['id'] ?>">

                            <?php foreach($certCourses as $c): ?>

                                <div class="course-row">

                                    <div><?= $c['title'] ?></div>

                                    <div class="course-actions">

                                        <?php if (!empty($c['file_path'])): ?>
                                            <a href="/mindgrow/<?= $c['file_path'] ?>" target="_blank">
                                                📂 ouvrir
                                            </a>
                                        <?php endif; ?>

                                        <a href="../controller/CourseController.php?edit=<?= $c['id'] ?>">
    ✏️ modifier
</a>

<a href="../controller/CourseController.php?delete=<?= $c['id'] ?>"
   onclick="return confirm('Supprimer ce cours ?')">
    🗑 supprimer Cours
</a>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

                </div>

            </div>
        </section>

        
<?php
$log = $conn->query("
    SELECT admin_logs.*, users.name AS admin_name
    FROM admin_logs
    LEFT JOIN users ON users.id = admin_logs.admin_id
    WHERE admin_logs.action = 'deleted_user'
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>

<h2 id="admin-logs">📜 Journaux d’administration </h2>

<div class="admin-logs">

<?php if ($log): ?>

    <div class="log-item">

        <div class="log-admin">
            👤 <?= htmlspecialchars($log['admin_name'] ?? 'Admin') ?>
        </div>

        <a href="/mindgrow/view/admin.php?show_log=<?= $log['id'] ?>#log-detail">
            <button class="log-btn">
                Voir users supprimés
            </button>
        </a>

    </div>

<?php else: ?>

    <p>Aucun log disponible</p>

<?php endif; ?>

</div>

<?php if (isset($_GET['show_log']) && $log): ?>

<?php
$data = json_decode($log['details'] ?? '', true);
$deletedUsers = $data['deleted_users'] ?? [];
?>

<div class="log-detail" id="log-detail">

    <h3>🗑 Users supprimés</h3>

    <?php if (!empty($deletedUsers)): ?>

        <?php foreach($deletedUsers as $u): ?>

            <p>
                <strong>ID :</strong>
                <?= htmlspecialchars($u['id'] ?? 'N/A') ?>

                |

                <strong>Nom :</strong>
                <?= htmlspecialchars($u['name'] ?? 'N/A') ?>

                |

                <strong>Date :</strong>
                <?= htmlspecialchars($u['deleted_at'] ?? 'N/A') ?>
            </p>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Aucun utilisateur supprimé.</p>

    <?php endif; ?>

</div>

<?php endif; ?>

       
    </div>
</div>
</div>



    <script src="/mindgrow/assets/js/script.js?v=<?= time() ?>"></script>