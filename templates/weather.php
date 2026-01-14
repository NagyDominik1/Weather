<h2>🌡 <?= $data['name'] ?></h2>

<ul>
    <li>Hőmérséklet: <?= $data['main']['temp'] ?> °C</li>
    <li>Páratartalom: <?= $data['main']['humidity'] ?> %</li>
    <li>Szél: <?= $data['wind']['speed'] ?> m/s</li>
    <li>Időjárás: <?= $data['weather'][0]['description'] ?></li>
</ul>

<a href="/iws-2025-hu/Projekt-iws/public/home">⬅ vissza</a>
