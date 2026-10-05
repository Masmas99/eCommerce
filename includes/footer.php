    </main>

    <!-- Footer Component -->
    <footer class="bg-black text-white mt-20 border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                
                <!-- Brand Column -->
                <div class="space-y-4">
                    <span class="text-2xl font-black tracking-[0.25em] uppercase border-y-2 border-white py-1 px-3 inline-block">PIDII</span>
                    <p class="text-xs text-neutral-400 leading-relaxed uppercase tracking-wider">
                        Modern Minimalist / High-Fashion E-Commerce. Clean lines, architectural tailoring, & sustainable craftsmanship.
                    </p>
                    <p class="text-xs text-neutral-500">© 2026 PIDII STUDIO. ALL RIGHTS RESERVED.</p>
                </div>

                <!-- Navigation Links -->
                <div class="space-y-3 text-xs uppercase tracking-widest">
                    <h4 class="font-bold text-white border-b border-neutral-800 pb-2">Navigation</h4>
                    <ul class="space-y-2 text-neutral-400">
                        <li><a href="<?= base_url('products.php') ?>" class="hover:text-white transition-colors">All Products</a></li>
                        <li><a href="<?= base_url('products.php?badge=New+Arrival') ?>" class="hover:text-white transition-colors">New Arrivals</a></li>
                        <li><a href="<?= base_url('products.php?badge=Limited+Edition') ?>" class="hover:text-white transition-colors">Limited Edition</a></li>
                        <li><a href="<?= base_url('cart.php') ?>" class="hover:text-white transition-colors">Shopping Bag</a></li>
                    </ul>
                </div>

                <!-- Customer Care -->
                <div class="space-y-3 text-xs uppercase tracking-widest">
                    <h4 class="font-bold text-white border-b border-neutral-800 pb-2">Customer Care</h4>
                    <ul class="space-y-2 text-neutral-400">
                        <li><a href="#" class="hover:text-white transition-colors">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Size Guide & Fit</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Newsletter Subscription -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest border-b border-neutral-800 pb-2">Subscribe to Editorial</h4>
                    <p class="text-xs text-neutral-400">Be first to receive access to limited releases and private lookbooks.</p>
                    <form onsubmit="event.preventDefault(); alert('Terima kasih telah berlangganan newsletter PIDII!');" class="space-y-2">
                        <input type="email" placeholder="YOUR EMAIL ADDRESS" required class="w-full bg-neutral-900 border border-neutral-700 p-3 text-xs text-white placeholder-neutral-500 focus:outline-none focus:border-white">
                        <button type="submit" class="w-full bg-white text-black font-extrabold text-xs uppercase tracking-widest py-3 hover:bg-neutral-200 transition-colors">JOIN CLUB</button>
                    </form>
                </div>
            </div>

           
    </footer>

</body>
</html>
