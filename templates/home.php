<h1>Válassz várost</h1>

<form method="get" action="/iws-2025-hu/Projekt-iws/public/weather">
    <select name="city_id">
        <?php foreach ($cities as $city): ?>
            <option value="<?= $city['id'] ?>">
                <?= htmlspecialchars($city['city_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Időjárás</button>
</form>
