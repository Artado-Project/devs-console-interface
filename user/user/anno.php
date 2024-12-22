<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

// Kullanıcının giriş yapmış olması gerekiyor
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit();
}

// Duyuruları alalım
$stmt = $db->query("SELECT * FROM announcements ORDER BY created_at DESC");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Kullanıcı oturum kontrolü
$user_id = $_SESSION['user_id'] ?? null;

// Varsayılan profil fotoğrafı
$default_profile_photo = 'https://artado.xyz/assest/img/artado-yeni.png';

// Kullanıcı bilgilerini çek
if ($user_id) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $profile_photo = $user['profile_photo'] ?: $default_profile_photo;
} else {
    $profile_photo = $default_profile_photo;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <script type="text/javascript" src="app.js" defer></script>
  <style>
        /* Sol Menü Profil Alanı */
        .side-profile {
      margin-top: -20px;
      /* Profil kısmını yukarı kaydırmak için */
      padding: 1.5rem;
      background-color: var(--dark-gray);
      border-radius: 10px;
      color: var(--pure-white);
    }

    .side-profile .info {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .side-profile .info img {
      border-radius: 50%;
      width: 64px;
      height: 64px;
      margin-bottom: 10px;
    }

    .side-profile .info p {
      font-size: 0.9rem;
      color: var(--light-gray);
      margin-top: 5px;
    }


    <style>

 /* Duyuru Box'larının arka planını siyah yapalım ve genişliği tam yapalım */
.announcement {
  background-color: #000000; /* Siyah arka plan */
  color: var(--pure-white);
  padding: 1.5rem;
  margin-top: 60px; /* Header'ın altına yerleştirebilmek için margin ekledik */
  margin-bottom: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%; /* Genişliği tam yapıyoruz */
}



.announcement-back {
    background-color: var(--dark-gray);
    color: var(--pure-white);
  padding: 1.5rem;
  margin-top: 60px; /* Header'ın altına yerleştirebilmek için margin ekledik */
  margin-bottom: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%; /* Genişliği tam yapıyoruz */
}

/* Duyuru içerik kısmının yazılarını da daha belirgin yapalım */
.announcement-content h3 {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.announcement-content p {
  font-size: 1rem;
}

.announcement-date p{
    color: black
}

/* Mobilde Duyuru Bölümünün Boyutlarını Ayarlama */
@media (max-width: 768px) {
  .announcement {
    padding: 1rem;
    margin-bottom: 1.5rem;
    margin-top: 50px; /* Mobilde de header'ın altına uygun boşluk ekliyoruz */
  }

  .announcement-content h3 {
    font-size: 1.2rem;
  }

  .announcement-content p {
    font-size: 0.9rem;
  }
}



/* Duyuru Bölümü */
/* Duyuru Bölümü */
.announcement {
  background-color: #e74c3c; /* Kırmızı renk */
  color: var(--pure-white);
  border: 1px solid var(--line-clr);
  border-radius: 1em;
  margin-bottom: 20px;
  padding: min(3em, 15%);

  h2, p { margin-top: 1em }
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  display: flex;
  justify-content: space-between;
  align-items: center;
}



.announcement-content h3 {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.announcement-content p {
  font-size: 1rem;
}



/* Mobilde Duyuru Bölümünün Boyutlarını Ayarlama */
@media (max-width: 768px) {
  .announcement {
    padding: 1rem;
    margin-bottom: 1.5rem;
    margin-top: 50px; /* Mobilde de header'ın altına uygun boşluk ekliyoruz */
  }

  .announcement-content h3 {
    font-size: 1.2rem;
  }

  .announcement-content p {
    font-size: 0.9rem;
  }
}



  </style>

</head>
<body>
  <nav id="sidebar">
    <ul>
      <li>
        <span class="logo">Artado Devs</span>
        <button onclick=toggleSidebar() id="toggle-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
        </button>
      </li>
      <li>
        <a href="index.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
          <span>Ana Sayfa</span>
        </a>
      </li>
      <li class="active">
        <a href="dashboard.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Zm80-360h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
          <span>Projelerim</span>
        </a>
      </li>
      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Zm400-160v40q0 17 11.5 28.5T600-320q17 0 28.5-11.5T640-360v-40h40q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480h-40v-40q0-17-11.5-28.5T600-560q-17 0-28.5 11.5T560-520v40h-40q-17 0-28.5 11.5T480-440q0 17 11.5 28.5T520-400h40Z"/></svg>
          <span>Proje Ekle</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Folder</a></li>
            <li><a href="#">Document</a></li>
            <li><a href="#">Project</a></li>
          </div>
        </ul>
      </li>
      <li>
        <button onclick=toggleSubMenu(this) class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m221-313 142-142q12-12 28-11.5t28 12.5q11 12 11 28t-11 28L250-228q-12 12-28 12t-28-12l-86-86q-11-11-11-28t11-28q11-11 28-11t28 11l57 57Zm0-320 142-142q12-12 28-11.5t28 12.5q11 12 11 28t-11 28L250-548q-12 12-28 12t-28-12l-86-86q-11-11-11-28t11-28q11-11 28-11t28 11l57 57Zm339 353q-17 0-28.5-11.5T520-320q0-17 11.5-28.5T560-360h280q17 0 28.5 11.5T880-320q0 17-11.5 28.5T840-280H560Zm0-320q-17 0-28.5-11.5T520-640q0-17 11.5-28.5T560-680h280q17 0 28.5 11.5T880-640q0 17-11.5 28.5T840-600H560Z"/></svg>
          <span>Todo-Lists</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li><a href="#">Çalışmalarım</a></li>
            <li><a href="#">Bireysel</a></li>
            <li><a href="#">Kodlar</a></li>
            <li><a href="#">Artado Notlarım</a></li>
          </div>
        </ul>
      </li>
      <li>
        <a href="anno.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"/></svg>
          <span>Duyurular</span>
        </a>
      </li>
      <li>
      <a href="profile.php">
  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
    <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/>
  </svg>
  <span>Profile</span>
  <img src="<?php echo $user['profile_photo']; ?>" class="profile-photo">
</a>

<style>
  .profile-photo {
    width: 64px; /* Fotoğrafın genişliği */
    height: 64px; /* Fotoğrafın yüksekliği */
    border-radius: 50%; /* Fotoğrafı yuvarlak yapar */
    border: 3px solid #ccc; /* Fotoğrafın etrafına 3px genişliğinde gri çerçeve ekler */
    margin-left: 10px; /* Yazı ile fotoğraf arasına biraz boşluk ekler */
  }
</style>

  </nav>
  <main>
    <?php foreach ($announcements as $announcement): ?>
        <div class="announcement">
          <div class="announcement-content">
            <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($announcement['description'])); ?></p>
          </div>
          <div class="announcement-date">
            <p><?php echo date('d-m-Y H:i', strtotime($announcement['created_at'])); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <div class="container">
    <!-- Son Eklenen Projeler -->
    <section class="project-section">
      <h3 class="text-2xl text-white">Son Eklenen Projeler</h3>

      <?php
      // Projeleri alalım
      $stmt = $db->query("SELECT p.title, u.username FROM projects p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 5");
      $recent_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <ul>
        <?php foreach ($recent_projects as $project): ?>
          <li><?php echo $project['title']; ?> <span>(Yükleyen: @<?php echo $project['username']; ?>)</span></li>
        <?php endforeach; ?>
      </ul>
    </section>
    </div>
    <div class="container">
      <h2>Lorem Ipsum</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore repudiandae labore veniam reprehenderit voluptatum, laboriosam perferendis fuga, dolore quam quas nostrum totam sunt esse expedita. Vero distinctio omnis accusantium. Quisquam ullam saepe cupiditate magni numquam totam perspiciatis error velit, debitis veniam labore possimus aut sunt, reiciendis natus. Impedit provident voluptatum nulla fuga error a magnam, corporis natus aperiam fugit quod perferendis quos quaerat, numquam sequi doloribus tenetur dolorem voluptate deleniti, odio minus. Deserunt eius quasi odit voluptas unde voluptatum dicta cumque exercitationem soluta beatae porro distinctio, delectus officiis, nobis officia ullam necessitatibus, rem natus corrupti nam! Est, nihil molestias fugiat sed quae enim commodi expedita soluta tempore molestiae fuga adipisci rem esse voluptates quos, ut quasi sunt ad a perspiciatis ducimus maxime animi. Adipisci officia doloribus magni alias maiores ab quo, eos mollitia sint esse. Labore odio, architecto nihil quaerat soluta blanditiis impedit laudantium esse officiis dolorum dolore libero, id sequi minima incidunt eum facilis itaque distinctio. Voluptas doloremque minus reiciendis ex beatae laudantium cum sequi repellat blanditiis molestiae. Cumque, libero nulla! Sit, quisquam magni dolore consectetur odio impedit adipisci voluptas ab, laboriosam autem nihil nam est ipsa excepturi obcaecati eos neque! Omnis similique qui veritatis. Repellat magni dolorem, facilis eaque, harum molestias, delectus est adipisci laudantium velit optio blanditiis debitis? Tenetur totam maiores animi officiis eligendi expedita nemo corrupti distinctio. Cum libero soluta beatae doloribus sit, repellendus nobis vel obcaecati velit dolorem voluptate magnam inventore quas pariatur quam reprehenderit molestiae hic sunt dicta illo amet quis magni accusamus sequi? Vel quis, dolores iusto suscipit excepturi laboriosam repellat consectetur! Maiores deserunt, pariatur nesciunt consequuntur recusandae minima assumenda consequatur inventore natus debitis illo velit voluptatum necessitatibus qui aspernatur illum impedit magni dignissimos ea, molestias tempora corporis, asperiores iusto possimus. Libero expedita aspernatur officia totam dolorum culpa, minus, alias adipisci eligendi suscipit voluptates, magnam laudantium? Inventore cupiditate perspiciatis mollitia excepturi, voluptatibus ducimus expedita provident. Dicta, odit. Odio, qui repudiandae! Maiores dignissimos, magnam deleniti reprehenderit ex cum ea eveniet placeat quae, ad at perspiciatis nobis corporis doloribus voluptatem nulla aliquam sunt accusamus facere quaerat necessitatibus ipsa! Nam quisquam dicta minima commodi nostrum. Exercitationem necessitatibus optio cumque voluptate modi amet consequuntur similique ex inventore explicabo doloremque esse sed sequi nemo rem, nostrum ullam. Totam repellat ut ipsa quisquam rem, nulla, suscipit debitis atque earum quis voluptates quaerat exercitationem architecto repellendus placeat, tenetur incidunt distinctio consectetur reiciendis minima officiis aliquam? Ipsum sequi hic officia iste a. Blanditiis, dicta! Eveniet molestias ut natus odio fugiat cum necessitatibus, architecto, quo a quisquam autem porro explicabo ipsam, nostrum deserunt possimus expedita eum est corporis quibusdam cupiditate! Fugiat, quaerat saepe. Harum modi eligendi beatae alias fugiat. Nostrum cum nisi saepe dicta iste cupiditate, deserunt omnis, doloremque a distinctio eum rem adipisci ab? Sapiente, dicta ipsam blanditiis earum omnis necessitatibus temporibus, excepturi accusantium delectus quo quod iusto ad aliquam nemo ducimus ab nobis inventore sequi veritatis? Nulla, dolorem. Voluptas, obcaecati non facilis repellendus ratione officiis veritatis, modi culpa rerum placeat voluptatum quia ex? Officia quos dolorum repellat deserunt voluptas praesentium.</p>
    </div>
  </main>
</body>
</html>
