<?php
require 'functions.php';

// Step 1: Fetch teams (Premier League = league 39, season 2023)
$teamsResponse = callApi('/teams', ['league' => 39, 'season' => 2023]);
$teams = $teamsResponse['response'] ?? [];

$players = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team_id'])) {
    $teamId = $_POST['team_id'];
    $playersResponse = callApi('/players', [
        'team' => $teamId,
        'season' => 2023
    ]);
    $players = $playersResponse['response'] ?? [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Player Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-700">Select a Football Team</h1>

        <form method="POST" class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-10">
            <select name="team_id" class="p-2 border border-gray-300 rounded w-64" required>
                <option value="">-- Select Team --</option>
                <?php foreach ($teams as $team): ?>
                    <option value="<?= $team['team']['id'] ?>">
                        <?= htmlspecialchars($team['team']['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Get Players
            </button>
        </form>

        <?php if (!empty($players)): ?>
            <h2 class="text-2xl font-semibold mb-4 text-center">Player List</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($players as $playerItem):
                    $player = $playerItem['player'];
                    $stats = $playerItem['statistics'][0] ?? [];
                ?>
                    <div class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center">
                        <img src="<?= $player['photo'] ?>" alt="Player Photo"
                             class="w-24 h-24 rounded-full object-cover mb-3 border-2 border-blue-400">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($player['name']) ?></h3>
                        <p>Age: <span class="font-medium"><?= $player['age'] ?></span></p>
                        <p>Nationality: <span class="font-medium"><?= $player['nationality'] ?></span></p>
                        <p>Appearances: <span class="font-medium"><?= $stats['games']['appearences'] ?? 0 ?></span></p>
                        <p>Goals: <span class="font-medium"><?= $stats['goals']['total'] ?? 0 ?></span></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
