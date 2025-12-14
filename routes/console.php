<?php

declare(strict_types=1);

use App\ScheduleTasks\CreateGame;

Schedule::call(new CreateGame())->daily();
