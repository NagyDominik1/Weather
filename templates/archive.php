<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Időjárás Archívum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="./"><i class="fa-solid fa-cloud-sun m-2"></i> WeatherApp</a>
        <div class="d-flex text-white">
            Bejelentkezve: <?= htmlspecialchars($_SESSION['email']) ?>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4 text-slate-800">Időjárási Archívum</h2>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="./archive" method="get" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Város kiválasztása</label>
                    <select name="city_id" class="form-select border-slate-200">
                        <option value="">Összes város</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= $city['id'] ?>" <?= (isset($_GET['city_id']) && $_GET['city_id'] == $city['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($city['city_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Dátum</label>
                    <input type="date" name="date_from" class="form-select border-slate-200" value="<?= $_GET['date_from'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm">
                        <i class="fa-solid fa-filter me-2"></i> Szűrés
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle text-center">
                <thead class="table-primary">
                <tr>
                    <th>Dátum/Idő</th>
                    <th>Város</th>
                    <th>Hőmérséklet</th>
                    <th>Leírás</th>
                    <th>Páratartalom</th>
                    <th>Szélsebesség</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="6" class="py-5 text-muted">Nincs találat a megadott feltételekkel.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($history as $row): ?>
                        <tr>
                            <td class="fw-bold"><?= date('Y.m.d. H:i', strtotime($row['dt'])) ?></td>
                            <td><?= htmlspecialchars($row['city_name']) ?></td>
                            <td class="text-primary fw-bold"><?= round($row['temperature']) ?>°C</td>
                            <td>
                                <img src="https://openweathermap.org/img/wn/<?= $row['icon'] ?>.png" width="30">
                                <?= htmlspecialchars($row['description']) ?>
                            </td>
                            <td><?= $row['humidity'] ?>%</td>
                            <td><?= $row['wind_speed'] ?> km/h</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="text-center py-4 text-muted mt-5 border-top">
    <p>&copy; 2026 WeatherApp - Integrált Web Rendszerek Projekt</p>
</footer>

</body>
</html>