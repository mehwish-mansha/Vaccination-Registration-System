
<?php

require_once __DIR__ . '/../includes/layout.php';

require_role('parent');


// ==================================
// GET PARENT'S CHILDREN
// ==================================

$stmt = $pdo->prepare("
    SELECT id, name, dob, gender
    FROM children
    WHERE parent_id = ?
    ORDER BY name ASC
");

$stmt->execute([user()['id']]);

$children = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ==================================
// GET AVAILABLE VACCINES
// ==================================

$vaccineStmt = $pdo->query("
    SELECT id, name, age_weeks, description
    FROM vaccines
    WHERE available = 1
    ORDER BY age_weeks ASC, id ASC
");

$vaccines = $vaccineStmt->fetchAll(PDO::FETCH_ASSOC);


// Today's date
$today = new DateTimeImmutable('today');

page_header('Vaccination Schedule');

?>


<section class="dashboard-section">

    <div class="dashboard-container">


        <!-- PAGE HEADING -->

        <div class="dashboard-welcome">

            <div>

                <span class="dashboard-label">
                    VACCINATION TRACKER
                </span>

                <h1>Vaccination Schedule</h1>

                <p>
                    Track due and completed vaccines for your children.
                </p>

            </div>

            <div class="welcome-icon">💉</div>

        </div>


        <?php if (empty($children)): ?>

            <div class="dashboard-panel empty-state">

                <div>👶</div>

                <h3>No Children Registered</h3>

                <p>
                    Please register a child before viewing
                    the vaccination schedule.
                </p>

                <a href="<?= e(url('parent/dashboard.php')) ?>"
                   class="dashboard-button">

                    Register Child

                </a>

            </div>

        <?php else: ?>


            <?php foreach ($children as $child): ?>

                <?php

                // Child's date of birth
                $dob = new DateTimeImmutable($child['dob']);

                // Calculate age
                $ageDays = $dob->diff($today)->days;
                $ageWeeks = intdiv($ageDays, 7);


                // Get completed vaccinations
                $recordStmt = $pdo->prepare("
                    SELECT vaccine_id, given_on
                    FROM vaccination_records
                    WHERE child_id = ?
                    AND status = 'vaccinated'
                ");

                $recordStmt->execute([$child['id']]);

                $records = [];

                foreach ($recordStmt->fetchAll(PDO::FETCH_ASSOC) as $record) {

                    $records[$record['vaccine_id']] = $record;

                }

                $completedCount = count($records);

                $totalCount = count($vaccines);

                ?>


                <!-- CHILD INFORMATION -->

                <div class="schedule-child">

                    <div class="schedule-child-heading">

                        <div>

                            <h2>
                                👶 <?= e($child['name']) ?>
                            </h2>

                            <p>
                                Date of Birth:
                                <?= e($child['dob']) ?>
                            </p>

                            <p>
                                Age:
                                <?= $ageWeeks ?> weeks
                            </p>

                        </div>

                        <div class="schedule-progress">

                            <strong>
                                <?= $completedCount ?>/<?= $totalCount ?>
                            </strong>

                            <span>Completed</span>

                        </div>

                    </div>


                    <!-- PROGRESS BAR -->

                    <?php

                    $progress = $totalCount > 0
                        ? min(100, round(($completedCount / $totalCount) * 100))
                        : 0;

                    ?>

                    <div class="progress-track">

                        <div
                            class="progress-fill"
                            style="width: <?= $progress ?>%"
                        ></div>

                    </div>


                    <!-- VACCINE LIST -->

                    <div class="vaccine-list">

                        <?php foreach ($vaccines as $vaccine): ?>

                            <?php

                            // Calculate vaccine due date
                            $dueDate = $dob->modify(
                                '+' . (int)$vaccine['age_weeks'] . ' weeks'
                            );

                            $isCompleted = isset($records[$vaccine['id']]);

                            $isDue = !$isCompleted && $dueDate <= $today;


                            if ($isCompleted) {

                                $status = 'Completed';
                                $statusClass = 'completed';

                            } elseif ($isDue) {

                                $status = 'Due';
                                $statusClass = 'due';

                            } else {

                                $status = 'Upcoming';
                                $statusClass = 'upcoming';

                            }

                            ?>


                            <div class="vaccine-item">

                                <div class="vaccine-info">

                                    <div class="vaccine-icon">
                                        💉
                                    </div>

                                    <div>

                                        <h3>
                                            <?= e($vaccine['name']) ?>
                                        </h3>

                                        <p>
                                            <?= e($vaccine['description'] ?? '') ?>
                                        </p>

                                        <small>
                                            Recommended age:
                                            <?= (int)$vaccine['age_weeks'] ?>
                                            weeks
                                        </small>

                                        <small>
                                            Due date:
                                            <?= $dueDate->format('d M Y') ?>
                                        </small>

                                        <?php if ($isCompleted): ?>

                                            <small>
                                                Given on:
                                                <?= e($records[$vaccine['id']]['given_on']) ?>
                                            </small>

                                        <?php endif; ?>

                                    </div>

                                </div>


                                <span class="vaccine-status <?= $statusClass ?>">

                                    <?= $status ?>

                                </span>

                            </div>


                        <?php endforeach; ?>

                    </div>

                </div>


            <?php endforeach; ?>


        <?php endif; ?>


        <a href="<?= e(url('parent/dashboard.php')) ?>"
           class="schedule-back">

            ← Back to Dashboard

        </a>


    </div>

</section>


<?php page_footer(); ?>
