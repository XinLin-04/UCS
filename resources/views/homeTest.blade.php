<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTAR Complainsion</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        
        /* Header Styles - Full Width */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #808080;
            padding: 10px 20px;
            color: white;
            width: 100%;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background-color: #f9c4c8;
            border-radius: 25%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 30px;
            color: #e8687c;
            font-weight: bold;
        }
        
        .site-title {
            font-size: 22px;
            font-weight: bold;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
        }
        
        .search-icon {
            width: 30px;
            height: 30px;
            background-color: #4682b4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 5px;
        }
        
        .search-input {
            padding: 8px 15px;
            border: none;
            border-radius: 15px;
            min-width: 200px;
        }
        
        /* Main Layout - 80% Width */
        .main-container {
            display: flex;
            height: calc(100vh - 70px);
            width: 80%;
            margin: 0 auto;
            aspect-ratio: 16/10;
        }
        
        /* Sidebar */
        .sidebar {
            width: 25%;
            background-color: #808080;
            color: black;
            padding: 20px;
            border-right: 1px solid #ccc;
            position: relative;
            height: calc(100vh - 70px);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #999;
        }
        
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #f5a742;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .avatar img {
            width: 50px;
            height: 50px;
        }
        
        .username {
            font-size: 18px;
            font-weight: bold;
        }
        
        .new-post {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #999;
            cursor: pointer;
        }
        
        .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
            color: white;
        }
        
        .my-posts-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .post-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .post-item {
            background-color: #ece2d0;
            padding: 15px;
            border-radius: 5px;
        }
        
        .logout {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: absolute;
            bottom: 20px;
        }
        
        /* Main Content */
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f0f0f0;
            border-left: 2px solid #9370db;
            overflow-y: auto;
            height: calc(100vh - 70px);
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: bold;
        }
        
        .filter-button {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }
        
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding-bottom: 20px;
        }
        
        .discussion-post {
            background-color: #ece2d0;
            padding: 20px;
            border-radius: 5px;
            min-height: 100px;
            aspect-ratio: 16/6;
        }
        
        /* 16:10 aspect ratio enforcement */
        @media (max-aspect-ratio: 16/10) {
            .main-container {
                height: auto;
                aspect-ratio: 16/10;
            }
            
            .sidebar, .content {
                height: 100%;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .main-container {
                width: 95%;
            }
        }
        
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                width: 100%;
                aspect-ratio: auto;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                min-height: 300px;
            }
            
            .content {
                height: auto;
            }
            
            .posts-grid {
                grid-template-columns: 1fr;
            }
            
            .logout {
                position: relative;
                margin-top: 40px;
                bottom: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header - Full Width -->
    <header class="header">
        <div class="logo-container">
            <div class="logo">u</div>
            <div class="site-title">UTAR Complainsion</div>
        </div>
        <div class="search-bar">
            <div class="search-icon">🔍</div>
            <input type="text" class="search-input">
        </div>
    </header>
    
    <!-- Main Content - 80% Width -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="avatar">
                    <img src="/api/placeholder/60/60" alt="User Avatar">
                </div>
                <div class="username">Lee Wei Sen</div>
            </div>
            
            <div class="new-post">
                <div class="icon">➕</div>
                <div class="text">New Post</div>
            </div>
            
            <div class="my-posts">
                <div class="my-posts-title">My Post</div>
                <div class="post-list">
                    <div class="post-item"></div>
                    <div class="post-item"></div>
                    <div class="post-item"></div>
                    <div class="post-item"></div>
                </div>
            </div>
            
            <div class="logout">
                <div class="icon">📤</div>
                <div class="text">Logout</div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="content">
            <div class="content-header">
                <div class="section-title">Recent Top Discussion (Year, Month, Week)</div>
                <button class="filter-button">⇅</button>
            </div>
            
            <div class="posts-grid">
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
                <div class="discussion-post"></div>
            </div>
        </div>
    </div>
</body>
</html>