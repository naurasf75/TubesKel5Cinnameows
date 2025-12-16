</main>

<footer class="bg-[#4B2E2A] text-[#FDF6EC]">


    <!-- FLOATING WA -->
    <a href="https://wa.me/6282328113713?text=Halo%20Admin%20Cinnameows%Bakery" target="_blank"
        class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M20.52 3.48A11.91 11.91 0 0012.06 0C5.47 0 .11 5.36.11 11.94c0 2.1.55 4.15 1.6 5.96L0 24l6.27-1.64a11.9 11.9 0 005.79 1.48h.01c6.59 0 11.95-5.36 11.95-11.94 0-3.19-1.24-6.19-3.5-8.42z" />
        </svg>
    </a>

    <div class="max-w-7xl mx-auto px-4 py-10 text-center">
        <h2 class="text-lg font-semibold tracking-wide">
            Cinnameows Bakery 🤍
        </h2>
        <p class="text-sm text-[#E7D3B0] mt-2">
            Made with love & fresh from the oven
        </p>

        <p class="text-xs mt-4 text-[#D6BFA8]">
            &copy; {{ date('Y') }} Cinnameows. All rights reserved.
        </p>

        <div class="mt-4 space-x-4 text-sm">
            <a href="#" class="hover:text-[#C08457] transition">Privacy Policy</a>
            <a href="#" class="hover:text-[#C08457] transition">Terms</a>
        </div>
    </div>

</footer>


<script>
    // Toggle mobile menu
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>

</body>

</html>