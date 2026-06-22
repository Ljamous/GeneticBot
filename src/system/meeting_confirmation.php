<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

session_start();
include './header.php';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card mx-auto"
            style="width:95%; max-width:600px; border-radius:20px; overflow:hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
            <div class="card-header text-center"
                style="background-color: #2D4B69; color: white; border-radius:20px 20px 0 0;">
                <h3 class="card-title">Meeting Confirmation</h3>
            </div>

            <div class="card-body p-4 text-center">
                <h1>
                    <?php
                    if (!empty($_SESSION['meeting_scheduled'])) {
                        echo "Thank you for scheduling a meeting!";
                        unset($_SESSION['meeting_scheduled']);
                    } elseif (!empty($_SESSION['meeting_updated'])) {
                        echo "Your meeting request has been updated!";
                        unset($_SESSION['meeting_updated']);
                    } else {
                        echo "Something went wrong!";
                        $showBackLink = true;
                    }
                    ?>
                </h1>

                <?php if (!empty($_SESSION['meeting_scheduled']) || !empty($_SESSION['meeting_updated'])): ?>
                    <p>
                        <?php
                        if (!empty($_SESSION['meeting_scheduled'])) {
                            echo "Your meeting request has been received. We will contact you shortly to confirm the details.";
                        } elseif (!empty($_SESSION['meeting_updated'])) {
                            echo "Your meeting has been successfully updated.";
                        }
                        ?>
                    </p>
                <?php else: ?>
                    <p>Please try scheduling your meeting again or contact us for assistance.</p>
                    <?php if (!empty($showBackLink)): ?>
                        <a href="schedule_meeting.php" class="btn btn-primary mt-3">Go back to Schedule Meeting</a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['meeting_scheduled']) || !empty($_SESSION['meeting_updated'])): ?>
                <div class="mt-4">
                    <a href="tutorial.php" class="btn btn-primary me-2">Go to Tutorials</a>
                    <a href="chat_embed.php" class="btn btn-secondary">Chat with GeneticBot</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include './footer.php'; ?>
