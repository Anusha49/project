<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Store News - Winter Sale</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom right, #d8ecff, #ffffff);
      margin: 0;
      padding: 0;
    }

    .news-container {
      max-width: 850px;
      margin: 50px auto;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .news-header {
      background: linear-gradient(blueviolet);
      color: #fff;
      text-align: center;
      padding: 25px;
      font-size: 26px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .news-banner {
      background: url('https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1200&q=80') no-repeat center/cover;
      height: 250px;
      position: relative;
    }

    .banner-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 30px;
      font-weight: bold;
      text-shadow: 0 2px 5px rgba(0,0,0,0.5);
    }

    .news-content {
      padding: 30px;
      text-align: center;
    }
    .footer{
        text-align:center;
        color: blueviolet;

    }

    .news-title {
      font-size: 24px;
      color: blueviolet;
      margin-bottom: 10px;
      font-weight: 700;
    }

    .news-date {
      font-size: 14px;
      color: #888;
      margin-bottom: 20px;
    }

    .news-text {
      font-size: 16px;
      color: #444;
      line-height: 1.7;
      margin-bottom: 25px;
    }

    .discount-box {
      display: inline-block;
      background: linear-gradient(135deg, #ff5f6d, #ffc371);
      color: #fff;
      font-weight: bold;
      font-size: 18px;
      padding: 10px 20px;
      border-radius: 50px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .shop-now {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 30px;
      background:blueviolet;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      border-radius: 30px;
      transition: 0.3s;
    }
.footer-note {
      background: #f2f6f9;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      color: #666;
    }
    .shop-now:hover {
      background:blueviolet;
      transform: translateY(-3px);
      box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    }

    .footer-note {
      background: #f2f6f9;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      color: #666;
    }
    .header{
        background-color: white;
        color: blueviolet;
        text-align: center;
    }
  </style>
</head>
<body>
<div class="header">
    <marquee class="marquee">Welcome to Smart Store</marquee>
    <p class="location">Kapan, Kathmandu</p>
  </div>

  <div class="news-container">
    <div class="news-header">📰 Store News & Updates</div>

    <div class="news-banner">
      <div class="banner-overlay">❄️ Winter Sale Is Here! ❄️</div>
    </div>

    <div class="news-content">
      <div class="news-title">Winter Sale 2025 Begins!</div>
      <div class="news-date">November 13, 2025</div>
      <div class="news-text">
        Embrace the winter season with warmth and style! Our <b>Winter Sale 2025</b> is live — enjoy
        exclusive discounts from <b>5% to 10% off</b> on cozy sweaters, jackets, and accessories.
        Hurry up, offers are valid for a limited time only!
      </div>
      <div class="discount-box">🔥 5% – 10% OFF 🔥</div>
      <br>
      <a href="#" class="shop-now">Shop Now</a>
    </div>
  </div>
<footer class="footer">
    <p>&copy; 2025 Smart Shop. All rights reserved.</p>
  </footer>
</body>
</html>
