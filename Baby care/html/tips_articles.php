<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require_once "../php/config/database.php";


/* =========================================================
   USER DATA
========================================================= */

$user_id = $_SESSION['user_id'];

$user_name = $_SESSION['user_name'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? '';
$profile_image = '';

$stmt = $conn->prepare("
    SELECT name, email, profile_image
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $user_name = $user['name'];
    $user_email = $user['email'];
    $profile_image = $user['profile_image'] ?? '';
}

$stmt->close();


/* =========================================================
   HELPER
========================================================= */

function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}


/* =========================================================
   ARTICLES
========================================================= */

$articles = [

    [
        'id' => 1,
        'title' => 'How to Calm a Crying Baby',
        'category' => 'Newborn',
        'category_key' => 'newborn',
        'date' => 'September 1, 2026',
        'image' => '../assets/images/1.jpg',

        'description' =>
            "Simple ways to understand your baby's crying and help them feel calm and comfortable.",

        'content' => '
            <p>
                Crying is one of the main ways babies communicate.
                A baby may cry because they are hungry, tired, uncomfortable,
                need a diaper change, or simply need comfort.
            </p>

            <h3>What Can Help?</h3>

            <ul>
                <li>Check if your baby is hungry or needs a diaper change.</li>
                <li>Hold your baby close and speak calmly.</li>
                <li>Reduce loud sounds and bright lights.</li>
                <li>Try gentle rocking or quiet movement.</li>
                <li>Create a calm and comfortable environment.</li>
            </ul>

            <p>
                Every baby is different, so parents may need to try a few
                calming techniques to discover what works best.
            </p>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/Pages/default.aspx'
    ],


    [
        'id' => 2,
        'title' => 'Healthy Feeding for Your Baby',
        'category' => 'Feeding',
        'category_key' => 'feeding',
        'date' => 'September 2, 2026',
        'image' => '../assets/images/2.jpg',

        'description' =>
            "Helpful feeding tips to make mealtimes easier and healthier for your little one.",

        'content' => '
            <p>
                Feeding your baby is an important part of their growth and
                development. As babies grow, their nutritional needs change.
                Offering appropriate foods and creating positive mealtime
                habits can help your baby develop a healthy relationship with food.
            </p>

            <h3>Why Is Healthy Feeding Important?</h3>

            <p>
                Babies need a variety of nutrients to support healthy growth,
                brain development, and energy. A balanced diet can include
                age-appropriate foods from different food groups while
                continuing breast milk or formula when recommended.
            </p>

            <h3>When Can Babies Start Solid Foods?</h3>

            <p>
                Most babies are ready to start complementary foods at around
                6 months of age. Signs of readiness can include being able to
                sit with support, having good head and neck control, and showing
                interest in food.
            </p>

            <h3>Healthy Foods for Your Baby</h3>

            <ul>
                <li>Vegetables and fruits.</li>
                <li>Whole grains.</li>
                <li>Age-appropriate protein-rich foods.</li>
                <li>Iron-rich foods.</li>
                <li>A healthy variety of tastes and textures.</li>
            </ul>

            <h3>Foods to Avoid</h3>

            <p>
                Some foods can be unsafe for babies. Avoid choking hazards
                and do not give honey to babies under 12 months.
                Foods and drinks high in added sugar or salt should also
                be limited.
            </p>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/feeding-nutrition/Pages/default.aspx'
    ],


    [
        'id' => 3,
        'title' => 'Better Sleep for Your Baby',
        'category' => 'Sleep',
        'category_key' => 'sleep',
        'date' => 'September 3, 2026',
        'image' => '../assets/images/3.jpg',

        'description' =>
            "Create a simple bedtime routine that helps your baby sleep comfortably.",

        'content' => '
            <p>
                Helping your baby develop healthy sleep habits can make bedtime
                easier for both babies and parents. A calm environment and a
                simple bedtime routine can help your baby feel comfortable
                and ready to sleep.
            </p>

            <h3>Why Is Sleep Important?</h3>

            <p>
                Sleep is an important part of your baby\'s growth and development.
                During sleep, babies rest, grow, and process new experiences.
            </p>

            <h3>Create a Simple Bedtime Routine</h3>

            <p>
                A consistent bedtime routine can help your baby understand
                that it is time to relax and sleep.
            </p>

            <ul>
                <li>Keep the room calm.</li>
                <li>Follow a simple routine.</li>
                <li>Keep bedtime consistent.</li>
                <li>Make the sleep area comfortable.</li>
                <li>Give your baby time to settle.</li>
            </ul>

            <h3>Safe Sleep Matters</h3>

            <p>
                Always place your baby on their back for sleep and use a firm,
                flat sleep surface. Keep pillows, blankets, toys, and other
                soft objects out of the baby\'s sleep area.
            </p>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/sleep/Pages/default.aspx'
    ],


    [
        'id' => 4,
        'title' => 'Supporting Baby Development',
        'category' => 'Development',
        'category_key' => 'development',
        'date' => 'September 4, 2026',
        'image' => '../assets/images/4.jpg',

        'description' =>
            "Easy activities that encourage your baby's learning and development.",

        'content' => '
            <p>
                Babies learn through everyday experiences, interaction,
                movement, sounds, and play. Simple activities can support
                their development while also creating enjoyable moments
                between parents and babies.
            </p>

            <h3>Simple Ways to Support Development</h3>

            <ul>
                <li>Talk and sing to your baby.</li>
                <li>Read simple books together.</li>
                <li>Give your baby safe opportunities to explore.</li>
                <li>Play simple interactive games.</li>
                <li>Encourage movement appropriate for their age.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.cdc.gov/ncbddd/actearly/milestones/'
    ],


    [
        'id' => 5,
        'title' => 'Preparing for a New Baby',
        'category' => 'Pregnancy',
        'category_key' => 'pregnancy',
        'date' => 'September 5, 2026',
        'image' => '../assets/images/5.jpg',

        'description' =>
            "Useful tips to help parents prepare for the arrival of their baby.",

        'content' => '
            <p>
                Preparing for a new baby can feel exciting and overwhelming.
                Planning ahead can help parents feel more organized and ready
                for the first days with their baby.
            </p>

            <h3>Things to Prepare</h3>

            <ul>
                <li>Prepare a safe sleeping space.</li>
                <li>Organize baby clothes and basic supplies.</li>
                <li>Prepare feeding essentials.</li>
                <li>Keep important healthcare information available.</li>
                <li>Ask family members for support when needed.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/prenatal/Pages/default.aspx'
    ],


    [
        'id' => 6,
        'title' => 'Keeping Your Baby Healthy',
        'category' => 'Health',
        'category_key' => 'health',
        'date' => 'September 6, 2026',
        'image' => '../assets/images/6.jpg',

        'description' =>
            "Important everyday habits that can help support your baby's health.",

        'content' => '
            <p>
                Everyday habits can play an important role in supporting
                your baby\'s overall health and wellbeing.
            </p>

            <h3>Healthy Daily Habits</h3>

            <ul>
                <li>Follow recommended vaccination schedules.</li>
                <li>Offer age-appropriate nutrition.</li>
                <li>Keep your baby\'s environment clean and safe.</li>
                <li>Encourage healthy sleep habits.</li>
                <li>Attend regular healthcare visits.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/Pages/default.aspx'
    ],


    [
        'id' => 7,
        'title' => 'Introducing New Foods',
        'category' => 'Feeding',
        'category_key' => 'feeding',
        'date' => 'August 30, 2026',
        'image' => '../assets/images/7.jpg',

        'description' =>
            "Learn how to introduce new foods to your baby's meals step by step.",

        'content' => '
            <p>
                Introducing new foods can be an exciting part of your baby\'s
                development. New foods should be introduced in an age-appropriate
                way while parents watch for reactions.
            </p>

            <h3>Helpful Tips</h3>

            <ul>
                <li>Introduce suitable foods gradually.</li>
                <li>Offer soft, age-appropriate textures.</li>
                <li>Introduce foods one at a time when appropriate.</li>
                <li>Watch for possible allergic reactions.</li>
                <li>Talk with your healthcare provider about concerns.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/feeding-nutrition/Pages/default.aspx'
    ],


    [
        'id' => 8,
        'title' => 'Creating a Bedtime Routine',
        'category' => 'Sleep',
        'category_key' => 'sleep',
        'date' => 'August 29, 2026',
        'image' => '../assets/images/8.jpg',

        'description' =>
            "A calm and consistent bedtime routine can make sleep easier for your baby.",

        'content' => '
            <p>
                A predictable bedtime routine can help babies understand
                that it is time to relax and prepare for sleep.
            </p>

            <h3>A Simple Routine</h3>

            <ul>
                <li>Dim the lights.</li>
                <li>Give your baby a bath if appropriate.</li>
                <li>Change into comfortable sleep clothes.</li>
                <li>Read a quiet story.</li>
                <li>Keep the room calm and comfortable.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/sleep/Pages/default.aspx'
    ],


    [
        'id' => 9,
        'title' => 'Newborn Care Basics',
        'category' => 'Newborn',
        'category_key' => 'newborn',
        'date' => 'August 28, 2026',
        'image' => '../assets/images/9.jpg',

        'description' =>
            "Simple newborn care tips every new parent should know.",

        'content' => '
            <p>
                Newborn babies need gentle care, attention, and a safe
                environment. Parents can learn many basic care routines
                during the first weeks.
            </p>

            <h3>Basic Newborn Care</h3>

            <ul>
                <li>Keep your baby clean and comfortable.</li>
                <li>Feed according to healthcare guidance.</li>
                <li>Support safe sleep habits.</li>
                <li>Keep regular healthcare appointments.</li>
                <li>Watch for changes in your baby\'s behavior.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/Pages/default.aspx'
    ],


    [
        'id' => 10,
        'title' => 'Fun Activities for Baby',
        'category' => 'Development',
        'category_key' => 'development',
        'date' => 'August 27, 2026',
        'image' => '../assets/images/10.jpg',

        'description' =>
            "Fun and simple activities that support your baby's early development.",

        'content' => '
            <p>
                Play is an important way for babies to explore their
                environment and develop new skills.
            </p>

            <h3>Activity Ideas</h3>

            <ul>
                <li>Sing simple songs together.</li>
                <li>Read colorful picture books.</li>
                <li>Play peekaboo.</li>
                <li>Let your baby safely explore different textures.</li>
                <li>Talk to your baby during everyday activities.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.cdc.gov/ncbddd/actearly/milestones/'
    ],


    [
        'id' => 11,
        'title' => 'Pregnancy Care Tips',
        'category' => 'Pregnancy',
        'category_key' => 'pregnancy',
        'date' => 'August 26, 2026',
        'image' => '../assets/images/11.jpg',

        'description' =>
            "Helpful daily habits for a comfortable and healthy pregnancy.",

        'content' => '
            <p>
                Pregnancy is an important period that requires regular
                healthcare and healthy daily habits.
            </p>

            <h3>Helpful Habits</h3>

            <ul>
                <li>Attend regular prenatal appointments.</li>
                <li>Follow your healthcare provider\'s recommendations.</li>
                <li>Eat a balanced diet.</li>
                <li>Stay appropriately active when advised.</li>
                <li>Get enough rest.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.acog.org/womens-health'
    ],


    [
        'id' => 12,
        'title' => 'Baby Health Checklist',
        'category' => 'Health',
        'category_key' => 'health',
        'date' => 'August 25, 2026',
        'image' => '../assets/images/12.jpg',

        'description' =>
            "A simple checklist to help parents stay organized with baby health care.",

        'content' => '
            <p>
                Keeping important baby health information organized can
                help parents remember regular care tasks and appointments.
            </p>

            <h3>Checklist</h3>

            <ul>
                <li>Vaccination appointments.</li>
                <li>Regular healthcare visits.</li>
                <li>Feeding information.</li>
                <li>Sleep routine.</li>
                <li>Important healthcare contacts.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/Pages/default.aspx'
    ],


    [
        'id' => 13,
        'title' => 'Positive Parenting Tips',
        'category' => 'Parenting',
        'category_key' => 'parenting',
        'date' => 'August 24, 2026',
        'image' => '../assets/images/13.jpg',

        'description' =>
            "Small parenting habits that can help create a loving environment for your baby.",

        'content' => '
            <p>
                Positive interactions can help babies feel safe, supported,
                and connected to their parents.
            </p>

            <h3>Positive Parenting Ideas</h3>

            <ul>
                <li>Respond to your baby with patience.</li>
                <li>Talk and interact frequently.</li>
                <li>Celebrate small developmental achievements.</li>
                <li>Create predictable routines.</li>
                <li>Give your baby plenty of affection.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/family-life/family-dynamics/Pages/default.aspx'
    ],


    [
        'id' => 14,
        'title' => 'Helping Baby Learn Through Play',
        'category' => 'Development',
        'category_key' => 'development',
        'date' => 'August 23, 2026',
        'image' => '../assets/images/14.jpg',

        'description' =>
            "Discover simple play ideas that support early learning and development.",

        'content' => '
            <p>
                Babies learn naturally through play, movement, sounds,
                and interaction with the people around them.
            </p>

            <h3>Play Ideas</h3>

            <ul>
                <li>Read picture books together.</li>
                <li>Sing songs and make simple sounds.</li>
                <li>Use safe toys appropriate for your baby\'s age.</li>
                <li>Encourage reaching and movement.</li>
                <li>Play simple social games.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.cdc.gov/ncbddd/actearly/milestones/'
    ],


    [
        'id' => 15,
        'title' => 'Easy Baby Meal Ideas',
        'category' => 'Feeding',
        'category_key' => 'feeding',
        'date' => 'August 22, 2026',
        'image' => '../assets/images/15.jpg',

        'description' =>
            "Simple ideas to make your baby's meals enjoyable and nutritious.",

        'content' => '
            <p>
                Offering a variety of suitable foods can make mealtimes
                interesting while helping babies become familiar with
                different tastes and textures.
            </p>

            <h3>Meal Ideas</h3>

            <ul>
                <li>Soft cooked vegetables.</li>
                <li>Mashed or soft fruits.</li>
                <li>Suitable grains.</li>
                <li>Age-appropriate protein foods.</li>
                <li>Iron-rich foods when appropriate.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/feeding-nutrition/Pages/default.aspx'
    ],


    [
        'id' => 16,
        'title' => 'Daily Baby Care',
        'category' => 'Parenting',
        'category_key' => 'parenting',
        'date' => 'August 21, 2026',
        'image' => '../assets/images/16.png',

        'description' =>
            "Simple daily care habits to keep your baby clean, comfortable and happy.",

        'content' => '
            <p>
                Daily baby care includes feeding, hygiene, sleep,
                comfort, play, and keeping the baby\'s environment safe.
            </p>

            <h3>Daily Care Habits</h3>

            <ul>
                <li>Keep feeding routines appropriate for your baby.</li>
                <li>Change diapers regularly.</li>
                <li>Keep your baby clean and comfortable.</li>
                <li>Follow safe sleep practices.</li>
                <li>Make time for interaction and play.</li>
            </ul>
        ',

        'learn_more' =>
            'https://www.healthychildren.org/English/ages-stages/baby/Pages/default.aspx'
    ]

];

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Baby Care - Tips & Articles</title>


    <!-- Main CSS -->

    <link
        rel="stylesheet"
        href="../css/style.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Poppins -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            padding: 0;

            background: #fafafa;

            font-family: 'Poppins', sans-serif;

            color: #1f2937;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .articles-sidebar {

            position: fixed;

            top: 0;
            left: 0;

            width: 250px;
            height: 100vh;

            background: #ffffff;

            border-right: 1px solid #e5e7eb;

            display: flex;
            flex-direction: column;

            z-index: 2000;

            overflow-y: auto;
        }


        /* LOGO */

        .articles-sidebar-logo {

            height: 96px;
            min-height: 96px;

            padding: 0 30px;

            display: flex;
            align-items: center;

            background: #ffffff;
        }


        .articles-sidebar-logo a {

            display: flex;
            align-items: center;

            text-decoration: none;

            width: auto;
            height: auto;
        }


        .articles-sidebar-logo img {
    display: block;

    width: 200px !important;
    max-width: 200px !important;

    height: auto !important;
    max-height: none !important;

    object-fit: contain;

    opacity: 1 !important;
    visibility: visible !important;
}


        /* NAV */

        .articles-sidebar-nav {

            padding: 25px 14px 0;

            display: flex;
            flex-direction: column;

            gap: 5px;

            flex: 1;
        }


        .articles-sidebar-nav a {

            min-height: 54px;

            padding: 0 18px;

            display: flex;
            align-items: center;

            border-radius: 12px;

            text-decoration: none;

            color: #1f2937;

            font-size: 15px;
            font-weight: 500;

            transition: 0.2s ease;
        }


        .articles-sidebar-nav a i {

            width: 28px;

            margin-right: 12px;

            font-size: 18px;

            color: #6b7280;
        }


        .articles-sidebar-nav a:hover {

            background: #fff1f4;

            color: #f26b8a;
        }


        .articles-sidebar-nav a:hover i {

            color: #f26b8a;
        }


        .articles-sidebar-nav a.active {

            background: #fff1f4;

            color: #f26b8a;
        }


        .articles-sidebar-nav a.active i {

            color: #f26b8a;
        }


        /* LOGOUT */

        .articles-sidebar-bottom {

            padding: 12px 14px 25px;

            border-top: 1px solid #e5e7eb;
        }


        .articles-sidebar-bottom a {

            min-height: 54px;

            padding: 0 18px;

            display: flex;
            align-items: center;

            border-radius: 12px;

            text-decoration: none;

            color: #1f2937;

            font-size: 15px;
            font-weight: 500;
        }


        .articles-sidebar-bottom a i {

            width: 28px;

            margin-right: 12px;

            color: #6b7280;
        }


        .articles-sidebar-bottom a:hover {

            background: #fff1f4;

            color: #f26b8a;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .articles-header {

            position: fixed;

            top: 0;
            left: 250px;

            width: calc(100% - 250px);

            height: 84px;

            background: #ffffff;

            border-bottom: 1px solid #e5e7eb;

            display: flex;
            align-items: center;
            justify-content: flex-end;

            padding: 0 30px;

            z-index: 1500;
        }


        /* SEARCH */

        .articles-header-search {

            position: absolute;

            left: 50%;
            top: 50%;

            transform: translate(-50%, -50%);

            width: 540px;
            height: 48px;

            display: flex;
            align-items: center;

            padding: 0 18px;

            border: 1px solid #e5e7eb;

            border-radius: 14px;

            background: #ffffff;
        }


        .articles-header-search i {

            margin-right: 13px;

            color: #6b7280;

            font-size: 20px;
        }


        .articles-header-search input {

            width: 100%;

            border: none;
            outline: none;

            background: transparent;

            font-family: 'Poppins', sans-serif;

            font-size: 15px;

            color: #1f2937;
        }


        .articles-header-search input::placeholder {

            color: #9ca3af;
        }


        /* HEADER RIGHT */

        .articles-header-right {

            display: flex;

            align-items: center;

            gap: 20px;
        }


        /* USER */

        .articles-user {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .articles-user-avatar {

            width: 42px;
            height: 42px;

            border-radius: 50%;

            overflow: hidden;

            background: #fff1f4;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #f26b8a;

            font-weight: 600;
        }


        .articles-user-avatar img {

            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        .articles-user-info {

            display: flex;

            flex-direction: column;

            line-height: 1.25;
        }


        .articles-user-info strong {

            font-size: 14px;
        }


        .articles-user-info span {

            font-size: 12px;

            color: #6b7280;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .articles-main {

            margin-left: 250px;

            padding: 120px 45px 60px;

            min-height: 100vh;
        }


        /* PAGE TITLE */

        .articles-heading {

            max-width: 1200px;

            margin: 0 auto 25px;
        }


        .articles-heading h1 {

            margin: 0;

            color: #1f2937;

            font-size: 32px;

            font-weight: 700;
        }


        .articles-heading p {

            margin: 8px 0 0;

            color: #6b7280;

            font-size: 15px;
        }


        /* =====================================================
           SEARCH + FILTER
        ===================================================== */

        .articles-tools {

            max-width: 1200px;

            margin: 0 auto 25px;

            display: flex;

            gap: 14px;

            align-items: center;
        }


        .articles-search-box {

            flex: 1;

            height: 48px;

            display: flex;

            align-items: center;

            padding: 0 16px;

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 12px;
        }


        .articles-search-box i {

            color: #9ca3af;

            margin-right: 12px;
        }


        .articles-search-box input {

            width: 100%;

            border: none;
            outline: none;

            font-family: 'Poppins', sans-serif;

            font-size: 14px;
        }


        .articles-select {

            height: 48px;

            padding: 0 14px;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            background: #ffffff;

            color: #374151;

            font-family: 'Poppins', sans-serif;

            outline: none;

            cursor: pointer;
        }


        /* =====================================================
           CATEGORY FILTERS
        ===================================================== */

        .articles-categories {

            max-width: 1200px;

            margin: 0 auto 30px;

            display: flex;

            gap: 10px;

            flex-wrap: wrap;
        }


        .category-btn {

            border: 1px solid #e5e7eb;

            background: #ffffff;

            color: #6b7280;

            padding: 10px 17px;

            border-radius: 25px;

            font-family: 'Poppins', sans-serif;

            font-size: 13px;

            font-weight: 500;

            cursor: pointer;

            transition: 0.2s ease;
        }


        .category-btn:hover {

            border-color: #f26b8a;

            color: #f26b8a;

            background: #fff8fa;
        }


        .category-btn.active {

            background: #f26b8a;

            border-color: #f26b8a;

            color: #ffffff;
        }


        /* =====================================================
           CARDS
        ===================================================== */

        .articles-grid {

            max-width: 1200px;

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 22px;
        }


        .article-card {

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 18px;

            overflow: hidden;

            cursor: pointer;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;

            display: flex;

            flex-direction: column;
        }


        .article-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 12px 30px rgba(31, 41, 55, 0.10);
        }


        /* IMAGE */

        .article-card-image {

            width: 100%;

            height: 190px;

            overflow: hidden;

            background: #fff1f4;
        }


        .article-card-image img {

            width: 100%;

            height: 100%;

            display: block;

            object-fit: cover;

            transition: transform 0.3s ease;
        }


        .article-card:hover
        .article-card-image img {

            transform: scale(1.04);
        }


        /* INFO */

        .article-card-info {

            padding: 20px;

            display: flex;

            flex-direction: column;

            flex: 1;
        }


        .article-card-category {

            width: fit-content;

            padding: 6px 12px;

            margin-bottom: 12px;

            background: #fff1f4;

            color: #f26b8a;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;
        }


        .article-card-info h3 {

            margin: 0 0 9px;

            color: #1f2937;

            font-size: 18px;

            font-weight: 600;

            line-height: 1.4;
        }


        .article-card-info p {

            margin: 0 0 18px;

            color: #6b7280;

            font-size: 13px;

            line-height: 1.7;

            flex: 1;
        }


        .article-card-footer {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;
        }


        .article-card-date {

            color: #9ca3af;

            font-size: 11px;
        }


        .read-article {

            color: #f26b8a;

            font-size: 13px;

            font-weight: 600;
        }


        /* =====================================================
           NO RESULTS
        ===================================================== */

        .no-results {

            display: none;

            max-width: 1200px;

            margin: 40px auto;

            text-align: center;

            color: #6b7280;
        }


        /* =====================================================
           MODAL
        ===================================================== */

        .article-modal {

            position: fixed;

            inset: 0;

            background: rgba(31, 41, 55, 0.55);

            display: none;

            align-items: center;
            justify-content: center;

            padding: 30px;

            z-index: 5000;
        }


        .article-modal.show {

            display: flex;
        }


        .article-modal-box {

            width: min(950px, 100%);

            max-height: 90vh;

            overflow-y: auto;

            background: #ffffff;

            border-radius: 22px;

            position: relative;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.20);
        }


        .modal-close {

            position: absolute;

            top: 18px;
            right: 18px;

            width: 40px;
            height: 40px;

            border: none;

            border-radius: 50%;

            background: #ffffff;

            color: #374151;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.12);

            font-size: 18px;

            cursor: pointer;

            z-index: 2;
        }


        .modal-close:hover {

            color: #f26b8a;
        }


        .modal-image {

            width: 100%;

            height: 330px;

            overflow: hidden;

            border-radius:
                22px 22px 0 0;
        }


        .modal-image img {

            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;
        }


        .modal-content {

            padding: 30px 35px 35px;
        }


        .modal-category {

            display: inline-block;

            padding: 7px 14px;

            margin-bottom: 12px;

            border-radius: 20px;

            background: #fff1f4;

            color: #f26b8a;

            font-size: 12px;

            font-weight: 600;
        }


        .modal-content h2 {

            margin: 0 0 8px;

            color: #1f2937;

            font-size: 30px;

            font-weight: 700;
        }


        .modal-date {

            margin-bottom: 25px;

            color: #9ca3af;

            font-size: 13px;
        }


        .modal-article-body {

            color: #4b5563;

            font-size: 15px;

            line-height: 1.9;
        }


        .modal-article-body h3 {

            margin-top: 25px;
            margin-bottom: 10px;

            color: #1f2937;

            font-size: 19px;

            font-weight: 600;
        }


        .modal-article-body p {

            margin-bottom: 15px;
        }


        .modal-article-body ul {

            padding-left: 22px;

            margin-bottom: 20px;
        }


        .modal-article-body li {

            margin-bottom: 8px;
        }


        .modal-learn-more {

            margin-top: 28px;

            padding-top: 22px;

            border-top: 1px solid #e5e7eb;

            display: flex;

            justify-content: flex-end;
        }


        .modal-learn-more a {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 11px 20px;

            background: #f26b8a;

            color: #ffffff;

            border-radius: 9px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 500;

            transition: 0.2s ease;
        }


        .modal-learn-more a:hover {

            background: #e85d7c;

            color: #ffffff;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1100px) {

            .articles-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .articles-header-search {

                width: 420px;
            }
        }


        @media (max-width: 800px) {

            .articles-sidebar {

                display: none;
            }

            .articles-header {

                left: 0;

                width: 100%;
            }

            .articles-main {

                margin-left: 0;

                padding-left: 25px;
                padding-right: 25px;
            }

            .articles-grid {

                grid-template-columns: 1fr;
            }

            .articles-tools {

                flex-direction: column;

                align-items: stretch;
            }

            .articles-header-search {

                width: 45%;
            }
        }


        @media (max-width: 600px) {

            .articles-header-search {

                display: none;
            }

            .articles-main {

                padding:
                    105px 18px 40px;
            }

            .articles-heading h1 {

                font-size: 27px;
            }

            .modal-image {

                height: 220px;
            }

            .modal-content {

                padding: 25px 20px 25px;
            }

            .modal-content h2 {

                font-size: 24px;
            }
        }

    </style>

</head>


<body>


<!-- =========================================================
     SIDEBAR
========================================================== -->

<aside class="articles-sidebar">


    <!-- LOGO -->

    <div class="articles-sidebar-logo">

        <a href="dashboard.php">

            <img
                src="../assets/icons/logo.png"
                alt="BabyCare"
            >

        </a>

    </div>


    <!-- NAVIGATION -->

    <nav class="articles-sidebar-nav">


        <a href="dashboard.php">

            <i class="bi bi-grid-fill"></i>

            <span>
                Dashboard
            </span>

        </a>


        <a href="profile.php">

            <i class="bi bi-person"></i>

            <span>
                Profile
            </span>

        </a>


        <a href="baby_profile.php">

            <i class="bi bi-people"></i>

            <span>
                Baby Profile
            </span>

        </a>


        <a href="foods.php">

            <i class="bi bi-egg-fried"></i>

            <span>
                Foods
            </span>

        </a>


        <a href="vaccination.php">

            <i class="bi bi-bandaid"></i>

            <span>
                Vaccination
            </span>

        </a>


        <a href="daily-tracker.php">

            <i class="bi bi-calendar-check"></i>

            <span>
                Daily Tracker
            </span>

        </a>


        <a
            href="tips_articles.php"
            class="active"
        >

            <i class="bi bi-journal-text"></i>

            <span>
                Tips & Articles
            </span>

        </a>


        <a href="settings.php">

            <i class="bi bi-gear"></i>

            <span>
                Settings
            </span>

        </a>


    </nav>


    <!-- LOGOUT -->

    <div class="articles-sidebar-bottom">

        <a href="../php/auth/logout.php">

            <i class="bi bi-box-arrow-right"></i>

            <span>
                Logout
            </span>

        </a>

    </div>


</aside>



<!-- =========================================================
     HEADER
========================================================== -->

<header class="articles-header">


    <!-- SEARCH -->

    <div class="articles-header-search">

        <i class="bi bi-search"></i>

        <input
            type="text"
            placeholder="Search articles..."
            id="headerSearch"
            autocomplete="off"
        >

    </div>


    <!-- RIGHT -->

    <div class="articles-header-right">


        <!-- NOTIFICATION -->

        <div class="dropdown">

            <button
                class="notification-btn"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="
                    border:none;
                    background:transparent;
                    position:relative;
                "
            >

                <i
                    class="bi bi-bell"
                    style="
                        font-size:23px;
                        color:#6b7280;
                    "
                ></i>

                <span
                    style="
                        position:absolute;
                        top:-4px;
                        right:-7px;
                        min-width:19px;
                        height:19px;
                        border-radius:50%;
                        background:#f26b8a;
                        color:#fff;
                        font-size:10px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-weight:600;
                    "
                >
                    3
                </span>

            </button>


            <div
                class="dropdown-menu dropdown-menu-end"
                style="
                    width:330px;
                    padding:10px;
                    border:none;
                    border-radius:14px;
                    box-shadow:0 12px 35px rgba(0,0,0,.12);
                "
            >

                <div
                    style="
                        padding:10px 12px;
                        border-bottom:1px solid #e5e7eb;
                        font-weight:600;
                    "
                >
                    Notifications
                </div>


                <div style="padding:12px;">

                    <strong style="font-size:13px;">
                        Upcoming Vaccination
                    </strong>

                    <p
                        style="
                            margin:4px 0;
                            color:#6b7280;
                            font-size:12px;
                        "
                    >
                        Check your baby's upcoming vaccination.
                    </p>

                </div>


                <div style="padding:12px;">

                    <strong style="font-size:13px;">
                        Try New Food
                    </strong>

                    <p
                        style="
                            margin:4px 0;
                            color:#6b7280;
                            font-size:12px;
                        "
                    >
                        Explore age-appropriate foods for your baby.
                    </p>

                </div>

            </div>

        </div>



        <!-- USER -->

        <div class="dropdown">

            <button
                class="dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="
                    border:none;
                    background:transparent;
                    display:flex;
                    align-items:center;
                    gap:10px;
                "
            >

                <div class="articles-user-avatar">

                    <?php if (!empty($profile_image)): ?>

                        <img
                            src="../<?= e($profile_image) ?>"
                            alt="Profile"
                        >

                    <?php else: ?>

                        <?= strtoupper(substr($user_name, 0, 1)) ?>

                    <?php endif; ?>

                </div>


                <div
                    class="articles-user-info"
                    style="text-align:left;"
                >

                    <strong>
                        <?= e($user_name) ?>
                    </strong>

                    <span>
                        Parent
                    </span>

                </div>


            </button>


            <ul class="dropdown-menu dropdown-menu-end">

                <li
                    style="
                        padding:10px 15px;
                    "
                >

                    <strong>
                        <?= e($user_name) ?>
                    </strong>

                    <br>

                    <small
                        style="
                            color:#6b7280;
                        "
                    >
                        <?= e($user_email) ?>
                    </small>

                </li>


                <li>
                    <hr class="dropdown-divider">
                </li>


                <li>

                    <a
                        class="dropdown-item"
                        href="profile.php"
                    >

                        <i class="bi bi-person me-2"></i>

                        My Profile

                    </a>

                </li>


                <li>

                    <a
                        class="dropdown-item"
                        href="settings.php"
                    >

                        <i class="bi bi-gear me-2"></i>

                        Settings

                    </a>

                </li>


                <li>
                    <hr class="dropdown-divider">
                </li>


                <li>

                    <a
                        class="dropdown-item"
                        href="../php/auth/logout.php"
                    >

                        <i class="bi bi-box-arrow-right me-2"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</header>



<!-- =========================================================
     MAIN
========================================================== -->

<main class="articles-main">


    <!-- HEADING -->

    <div class="articles-heading">

        <h1>
            Tips & Articles
        </h1>

        <p>
            Helpful tips and trusted information for you and your baby.
        </p>

    </div>



    <!-- TOOLS -->

    <div class="articles-tools">


        <div class="articles-search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="articleSearch"
                placeholder="Search articles..."
                autocomplete="off"
            >

        </div>


        <select
            id="categoryFilter"
            class="articles-select"
        >

            <option value="all">
                All Categories
            </option>

            <option value="pregnancy">
                Pregnancy
            </option>

            <option value="newborn">
                Newborn
            </option>

            <option value="feeding">
                Feeding
            </option>

            <option value="sleep">
                Sleep
            </option>

            <option value="health">
                Health
            </option>

            <option value="development">
                Development
            </option>

            <option value="parenting">
                Parenting
            </option>

        </select>


        <select
            id="sortArticles"
            class="articles-select"
        >

            <option value="recent">
                Most Recent
            </option>

            <option value="az">
                A - Z
            </option>

        </select>


    </div>



    <!-- CATEGORY BUTTONS -->

    <div class="articles-categories">

        <button
            class="category-btn active"
            data-category="all"
        >
            All
        </button>


        <button
            class="category-btn"
            data-category="pregnancy"
        >
            Pregnancy
        </button>


        <button
            class="category-btn"
            data-category="newborn"
        >
            Newborn
        </button>


        <button
            class="category-btn"
            data-category="feeding"
        >
            Feeding
        </button>


        <button
            class="category-btn"
            data-category="sleep"
        >
            Sleep
        </button>


        <button
            class="category-btn"
            data-category="health"
        >
            Health
        </button>


        <button
            class="category-btn"
            data-category="development"
        >
            Development
        </button>


        <button
            class="category-btn"
            data-category="parenting"
        >
            Parenting
        </button>

    </div>



    <!-- =====================================================
         ARTICLES GRID
    ====================================================== -->

    <div
        class="articles-grid"
        id="articlesGrid"
    >


        <?php foreach ($articles as $article): ?>


            <article
                class="article-card"
                data-category="<?= e($article['category_key']) ?>"
                data-title="<?= e($article['title']) ?>"
                data-date="<?= e($article['date']) ?>"
                data-id="<?= $article['id'] ?>"
            >


                <!-- IMAGE -->

                <div class="article-card-image">

                    <img
                        src="<?= e($article['image']) ?>"
                        alt="<?= e($article['title']) ?>"
                    >

                </div>


                <!-- INFO -->

                <div class="article-card-info">


                    <span class="article-card-category">

                        <?= e($article['category']) ?>

                    </span>


                    <h3>

                        <?= e($article['title']) ?>

                    </h3>


                    <p>

                        <?= e($article['description']) ?>

                    </p>


                    <div class="article-card-footer">

                        <span class="article-card-date">

                            <?= e($article['date']) ?>

                        </span>


                        <span class="read-article">

                            Read More
                            <i class="bi bi-arrow-right"></i>

                        </span>

                    </div>


                </div>

            </article>


        <?php endforeach; ?>


    </div>



    <!-- NO RESULTS -->

    <div
        class="no-results"
        id="noResults"
    >

        <i
            class="bi bi-search"
            style="
                font-size:30px;
                display:block;
                margin-bottom:10px;
            "
        ></i>

        <strong>
            No articles found
        </strong>

        <p>
            Try another search or category.
        </p>

    </div>


</main>



<!-- =========================================================
     ARTICLE MODAL
========================================================== -->

<div
    class="article-modal"
    id="articleModal"
>


    <div class="article-modal-box">


        <!-- CLOSE -->

        <button
            class="modal-close"
            id="closeModal"
            type="button"
        >

            <i class="bi bi-x-lg"></i>

        </button>


        <!-- IMAGE -->

        <div class="modal-image">

            <img
                id="modalImage"
                src=""
                alt=""
            >

        </div>


        <!-- CONTENT -->

        <div class="modal-content">


            <span
                class="modal-category"
                id="modalCategory"
            >
            </span>


            <h2 id="modalTitle">
            </h2>


            <div
                class="modal-date"
                id="modalDate"
            >
            </div>


            <div
                class="modal-article-body"
                id="modalBody"
            >
            </div>


            <!-- LEARN MORE -->

            <div class="modal-learn-more">

                <a
                    href="#"
                    id="modalLearnMore"
                    target="_blank"
                    rel="noopener noreferrer"
                >

                    Learn More

                    <i class="bi bi-box-arrow-up-right"></i>

                </a>

            </div>


        </div>

    </div>

</div>



<!-- =========================================================
     ARTICLE DATA FOR JAVASCRIPT
========================================================== -->

<script>

    const articles = <?= json_encode(
        $articles,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    ) ?>;


    const cards =
        document.querySelectorAll('.article-card');


    const searchInput =
        document.getElementById('articleSearch');


    const headerSearch =
        document.getElementById('headerSearch');


    const categoryFilter =
        document.getElementById('categoryFilter');


    const sortArticles =
        document.getElementById('sortArticles');


    const categoryButtons =
        document.querySelectorAll('.category-btn');


    const articlesGrid =
        document.getElementById('articlesGrid');


    const noResults =
        document.getElementById('noResults');


    const modal =
        document.getElementById('articleModal');


    const closeModal =
        document.getElementById('closeModal');


    const modalImage =
        document.getElementById('modalImage');


    const modalCategory =
        document.getElementById('modalCategory');


    const modalTitle =
        document.getElementById('modalTitle');


    const modalDate =
        document.getElementById('modalDate');


    const modalBody =
        document.getElementById('modalBody');


    const modalLearnMore =
        document.getElementById('modalLearnMore');



    /* =====================================================
       FILTER STATE
    ====================================================== */

    let currentCategory = 'all';

    let currentSearch = '';



    /* =====================================================
       DISPLAY ARTICLES
    ====================================================== */

    function displayArticles() {


        let visibleCount = 0;


        cards.forEach(card => {


            const title =
                card.dataset.title.toLowerCase();


            const category =
                card.dataset.category;


            const matchesSearch =
                title.includes(currentSearch.toLowerCase());


            const matchesCategory =
                currentCategory === 'all' ||
                category === currentCategory;


            if (
                matchesSearch &&
                matchesCategory
            ) {

                card.style.display = 'flex';

                visibleCount++;

            } else {

                card.style.display = 'none';

            }

        });


        noResults.style.display =
            visibleCount === 0
                ? 'block'
                : 'none';

    }



    /* =====================================================
       SEARCH
    ====================================================== */

    function applySearch(value) {

        currentSearch = value;

        searchInput.value = value;

        if (headerSearch) {
            headerSearch.value = value;
        }

        displayArticles();

    }


    searchInput.addEventListener(
        'input',
        function () {

            applySearch(this.value);

        }
    );


    headerSearch.addEventListener(
        'input',
        function () {

            applySearch(this.value);

        }
    );



    /* =====================================================
       CATEGORY BUTTONS
    ====================================================== */

    categoryButtons.forEach(button => {


        button.addEventListener(
            'click',
            function () {


                categoryButtons.forEach(btn => {

                    btn.classList.remove('active');

                });


                this.classList.add('active');


                currentCategory =
                    this.dataset.category;


                categoryFilter.value =
                    currentCategory;


                displayArticles();

            }
        );

    });



    /* =====================================================
       CATEGORY SELECT
    ====================================================== */

    categoryFilter.addEventListener(
        'change',
        function () {


            currentCategory =
                this.value;


            categoryButtons.forEach(button => {

                button.classList.toggle(
                    'active',
                    button.dataset.category === currentCategory
                );

            });


            displayArticles();

        }
    );



    /* =====================================================
       SORT
    ====================================================== */

    sortArticles.addEventListener(
        'change',
        function () {


            const sorted =
                [...cards];


            if (this.value === 'az') {

                sorted.sort(
                    (a, b) =>
                        a.dataset.title.localeCompare(
                            b.dataset.title
                        )
                );

            } else {

                sorted.sort(
                    (a, b) =>
                        Number(b.dataset.id) -
                        Number(a.dataset.id)
                );

            }


            sorted.forEach(card => {

                articlesGrid.appendChild(card);

            });


            displayArticles();

        }
    );



    /* =====================================================
       OPEN MODAL
    ====================================================== */

    cards.forEach(card => {


        card.addEventListener(
            'click',
            function () {


                const id =
                    Number(this.dataset.id);


                const article =
                    articles.find(
                        item => item.id === id
                    );


                if (!article) {
                    return;
                }


                modalImage.src =
                    article.image;


                modalImage.alt =
                    article.title;


                modalCategory.textContent =
                    article.category;


                modalTitle.textContent =
                    article.title;


                modalDate.textContent =
                    article.date;


                modalBody.innerHTML =
                    article.content;


                modalLearnMore.href =
                    article.learn_more;


                modal.classList.add('show');


                document.body.style.overflow =
                    'hidden';

            }
        );

    });



    /* =====================================================
       CLOSE MODAL
    ====================================================== */

    closeModal.addEventListener(
        'click',
        closeArticleModal
    );


    modal.addEventListener(
        'click',
        function (event) {

            if (event.target === modal) {

                closeArticleModal();

            }

        }
    );


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                modal.classList.contains('show')
            ) {

                closeArticleModal();

            }

        }
    );


    function closeArticleModal() {

        modal.classList.remove('show');

        document.body.style.overflow =
            '';

    }



    /* =====================================================
       INITIAL
    ====================================================== */

    displayArticles();

</script>



<!-- Bootstrap -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<!-- Your JS -->

<script src="../js/script.js"></script>


</body>

</html>