<?php
// Dashboard - a Database és VisitorTracker már be van töltve!
$db = Database::getConnection();
$tracker = new VisitorTracker($db);
$stats = $tracker->getStats();

// További statisztikák
$userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$cityCount = $db->query("SELECT COUNT(*) FROM cities")->fetchColumn();
$favoriteCount = $db->query("SELECT COUNT(*) FROM favorite_cities")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WeatherBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                <a href="/iws-2025-hu/Projekt-iws/public/admin" class="list-group-item list-group-item-action active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/admin/visitors" class="list-group-item list-group-item-action">
                    <i class="bi bi-eye"></i> Látogatók
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/admin/users" class="list-group-item list-group-item-action">
                    <i class="bi bi-people"></i> Felhasználók
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/admin/weather-data" class="list-group-item list-group-item-action">
                    <i class="bi bi-cloud-sun"></i> Időjárás adatok
                </a>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10">
            <h1 class="mb-4">
                <i class="bi bi-speedometer2"></i> Admin Dashboard
            </h1>

            <!-- STATISZTIKA KÁRTYÁK -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-eye"></i> Látogatások
                            </h5>
                            <h2><?= number_format($stats['total_visits'] ?? 0) ?></h2>
                            <small>Összes tracking</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-people"></i> Felhasználók
                            </h5>
                            <h2><?= $userCount ?></h2>
                            <small>Regisztrált fiókok</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-geo-alt"></i> Városok
                            </h5>
                            <h2><?= $cityCount ?></h2>
                            <small>Adatbázisban</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-star"></i> Kedvencek
                            </h5>
                            <h2><?= $favoriteCount ?></h2>
                            <small>Mentett városok</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ESZKÖZ STATISZTIKA -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-phone"></i> Eszköz megoszlás
                        </div>
                        <div class="card-body">
                            <?php
                            $mobile = $stats['device_breakdown']['mobile'] ?? 0;
                            $desktop = $stats['device_breakdown']['desktop'] ?? 0;
                            $total = $mobile + $desktop;
                            $mobilePercent = $total > 0 ? round(($mobile / $total) * 100) : 0;
                            $desktopPercent = $total > 0 ? round(($desktop / $total) * 100) : 0;
                            ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>📱 Mobil</span>
                                    <span><?= $mobile ?> (<?= $mobilePercent ?>%)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: <?= $mobilePercent ?>%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>💻 Desktop</span>
                                    <span><?= $desktop ?> (<?= $desktopPercent ?>%)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: <?= $desktopPercent ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-globe"></i> Top országok
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Ország</th>
                                    <th class="text-end">Látogatások</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($stats['top_countries'] ?? [] as $country): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($country['country']) ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-primary"><?= $country['count'] ?></span>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>