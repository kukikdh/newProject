
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="../public/css/home.css" />  
    <link rel="stylesheet" href="../public/css/base.css" />
</head>
<body>
<!-- first hearder ko part-->
  <header>
    <h1>StudyNote</h1>
    <nav>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a href="../auth/login.php" class="btn-login">Login / Sign Up</a></li>
      </ul>
    </nav>
  </header>

  <!-- Home top ko -->
  <section class="home" id="home">
    <h2>Smarter Study Starts Here</h2>
    <p>Organize your notes, track progress, and collaborate with classmates — all in one place.</p>
    <button class="btn-cta">Get Started</button>
  </section>

  <!-- Features -->
  <section class="features" id="features">
    <h3>Features ✨</h3>
    <div class="feature-boxes">
      <div class="feature">
        <h4>📁 File Sorter</h4>
        <p>Keep all your study files neatly organized.</p>
      </div>
      <div class="feature">
        <h4>📝 Note Builder</h4>
        <p>Create, edit, and format study notes with ease.</p>
      </div>
      <div class="feature">
        <h4>📊 Progress Tracker</h4>
        <p>Track your learning goals and achievements.</p>
      </div>
    </div>
  </section>

  <!-- further answesr and question -->
  <section class="faq" id="faq">
    <h3>Frequently Asked Questions ❓</h3>
    <div class="faq-item">
      <div class="faq-question">Is StudyNote free to use?</div>
      <div id="child1" class="faq-answer">Yes! StudyNote is completely free for students to use.</div>
    </div>
    <div class="faq-item">
      <div class="faq-question">Can I access StudyNote on mobile?</div>
      <div class="faq-answer">Absolutely! StudyNote is mobile-friendly and works on all devices.</div>
    </div>
    <div class="faq-item">
      <div class="faq-question">Do I need to install anything?</div>
      <div class="faq-answer">No installation needed — just open your browser and start learning!</div>
    </div>
  </section>
  <!-- News wala  -->
  <section class="newsletter" id="newsletter">
    <h3>Stay Updated</h3>
    <p>Get study tips, app updates, and exclusive offers in your inbox.</p>
    <form id="newsletterForm">
      <input type="email" id="email" placeholder="Enter your email" required>
      <button type="submit">Subscribe</button>
    </form>
  </section>
<!--yo footer ko-->
  <footer>
    <h2>StudyNote</h2>
    <p>&copy; 2025 StudyNote. All rights reserved.</p>
  </footer>
</body>

<script>
    // Smooth Scroll for nav links
    document.querySelectorAll("nav a").forEach(anchor => {
      anchor.addEventListener("click", function(e) {
        if (this.getAttribute("href").startsWith("#")) {
          e.preventDefault();
          document.querySelector(this.getAttribute("href")).scrollIntoView({
            behavior: "smooth"
          });
        }
      });
    });

    // FAQ ko lage
    document.querySelectorAll(".faq-item").forEach(item => {
      item.addEventListener("click", () => {
        console.log('clicked')
        const answer = item.querySelector(".faq-answer");
        answer.style.display = (answer.style.display || answer.style.display == 'none') ? "block" : "none";
      });
    });

    // Newsletter pop up mathi aaune 
    // document.getElementById("newsletterForm").addEventListener("submit", function(e) {
    //   e.preventDefault();
    //   const email = document.getElementById("email").value;
    //   const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    //   if (emailPattern.test(email)) {
    //     alert("Thank you for subscribing!");
    //     this.reset();
    //   } else {
    //     alert("Please enter a valid email address.");
    //   }
    // });

    // function opennnn(event) {
    //   console.log('clicked')
    //   console.log(event)
    //   console.log(document.getElementById('child1').style.display)

    //   document.getElementById('child1').style.display = 'block'
    // }


</script>
</html>