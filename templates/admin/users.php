<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználók - Admin - WeatherBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DATATABLES CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/iws-2025-hu/Projekt-iws/public/admin">
            <i class="bi bi-speedometer2"></i> WeatherBase Admin
        </a>
        <div class="d-flex">
                <span class="text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['email'] ?? 'Admin') ?>
                </span>
            <a href="/iws-2025-hu/Projekt-iws/public/" class="btn btn-sm btn-outline-light">
                <i class="bi bi-house"></i> Főoldal
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2">
            <div class="list-group">
                <a href="/iws-2025-hu/Projekt-iws/public/admin" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/admin/visitors" class="list-group-item list-group-item-action">
                    <i class="bi bi-eye"></i> Látogatók
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/admin/users" class="list-group-item list-group-item-action active">
                    <i class="bi bi-people"></i> Felhasználók
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/admin/weather-data" class="list-group-item list-group-item-action">
                    <i class="bi bi-cloud-sun"></i> Időjárás adatok
                </a>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-people"></i> Felhasználók</h1>
                <span class="badge bg-primary fs-5"><?= count($users) ?> felhasználó</span>
            </div>

            <!-- STATISZTIKA KÁRTYÁK -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>✅ Aktív felhasználók</h5>
                            <h3 class="text-success">
                                <?= count(array_filter($users, fn($u) => $u['is_active'] == 1)) ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>⏳ Inaktív felhasználók</h5>
                            <h3 class="text-warning">
                                <?= count(array_filter($users, fn($u) => $u['is_active'] == 0)) ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>📅 Mai regisztrációk</h5>
                            <h3 class="text-info">
                                <?= count(array_filter($users, fn($u) => date('Y-m-d', strtotime($u['created_at'])) == date('Y-m-d'))) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATATABLES TÁBLA -->
            <div class="card">
                <div class="card-body">
                    <table id="usersTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Státusz</th>
                            <th>Regisztráció</th>
                            <th>Műveletek</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td>
                                    <i class="bi bi-envelope"></i>
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>
                                <td>
                                    <?php if ($user['is_active']): ?>
                                        <span class="badge bg-success">✅ Aktív</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">⏳ Inaktív</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></small>
                                </td>
                                <td>
                                    <a href="/iws-2025-hu/Projekt-iws/public/profile?user_id=<?= $user['id'] ?>"
                                       class="btn btn-sm btn-primary"
                                       title="Profil megtekintése">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DATATABLES JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/hu.json'
            },
            order: [[0, 'desc']], // ID szerint csökkenő
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Nyomtatás',
                    className: 'btn btn-primary btn-sm'
                }
            ]
        });
    });
</script>
</body>
</html>