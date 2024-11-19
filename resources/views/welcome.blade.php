<!DOCTYPE html>
<html :lang="currentLanguage" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'>
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="https://cdn-icons-png.freepik.com/256/15203/15203047.png?semt=ais_hybrid" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body :class="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <div id="app">
        <task-list></task-list>
        <footer class="footer">
            <p>&copy; 2024 Zyad. All Rights Reserved.</p>
        </footer>
    </div>
</body>
</html>
