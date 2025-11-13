<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">

        {{-- LOGO + DESCRIPCIÓN --}}
        <div>
            <h2 class="text-2xl font-bold text-white mb-3">
                Cakes<span class="text-orange-400">Delights</span>
            </h2>
            <p class="text-sm leading-relaxed">
                Deliciosos pasteles y postres hechos con amor.
                Endulzamos tus momentos más especiales 🍰
            </p>

            <div class="flex space-x-4 mt-4">
                <a href="#" class="text-gray-400 hover:text-blue-400 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-pink-400 transition">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-sky-400 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>

        {{-- ENLACES --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-3">Enlaces</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('welcome.index') }}" class="hover:text-white transition">Inicio</a></li>
                <li><a href="{{ route('families.show', '1') }}" class="hover:text-white transition">Pasteles</a></li>
                <li><a href="{{ route('families.show', '2') }}" class="hover:text-white transition">Postres</a></li>
                <li><a href="{{ route('families.show', '3') }}" class="hover:text-white transition">Cupcakes</a></li>
                <li><a href="{{ route('nosotros.index') }}" class="hover:text-white transition">Sobre Nosotros</a></li>
            </ul>
        </div>

        {{-- HORARIO --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-3">Horario</h3>
            <ul class="space-y-1 text-sm">
                <li>Lunes - Viernes: 9:00 AM - 7:00 PM</li>
                <li>Sábado: 10:00 AM - 5:00 PM</li>
                <li>Domingo: Cerrado</li>
            </ul>
        </div>

        {{-- CONTACTO --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-3">Contáctanos</h3>
            <ul class="space-y-2 text-sm">
                <li><i class="fas fa-map-marker-alt mr-2 text-orange-400"></i> San Luis Potosí, México</li>
                <li><i class="fas fa-phone-alt mr-2 text-orange-400"></i> +52 444 123 4567</li>
                <li><i class="fas fa-envelope mr-2 text-orange-400"></i> contacto@cakesdelights.com</li>
            </ul>
        </div>
    </div>

    {{-- COPYRIGHT --}}
    <div class="border-t border-gray-700 py-4 text-center text-sm text-gray-400">
        © {{ date('Y') }} <span class="text-white font-semibold">Cakes Delights</span>. Todos los derechos reservados.
    </div>
</footer>
