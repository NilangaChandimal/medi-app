<div class="mt-8 border-t pt-6">
    <h3 class="text-lg font-semibold mb-4">Rate This Pharmacy</h3>

    @if(!$existingRating)
    <form action="{{ route('customer.ratings.store', $order->id) }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="rating-input flex items-center gap-2">
                @for($i = 1; $i <= 5; $i++)
                <input type="radio"
                       id="star{{ $i }}"
                       name="rating"
                       value="{{ $i }}"
                       class="hidden"
                       {{ old('rating') == $i ? 'checked' : '' }}>
                <label for="star{{ $i }}"
                       class="text-3xl cursor-pointer
                              {{ $i <= old('rating', 0) ? 'text-yellow-400' : 'text-gray-300' }}">
                    ★
                </label>
                @endfor
            </div>

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700">Review (optional)</label>
                <textarea name="comment" id="comment" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Share your experience...">{{ old('comment') }}</textarea>
            </div>

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Submit Review
            </button>
        </div>
    </form>
    @else
    <div class="bg-green-50 p-4 rounded-lg">
        <p class="text-green-700">⭐ You rated this pharmacy {{ $existingRating->rating }} stars</p>
        @if($existingRating->comment)
        <blockquote class="mt-2 text-green-700">
            "{{ $existingRating->comment }}"
        </blockquote>
        @endif
    </div>
    @endif
</div>

{{-- @push('scripts') --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ratingInputs = document.querySelectorAll('.rating-input input');
        const ratingLabels = document.querySelectorAll('.rating-input label');

        function highlightStars(rating) {
            ratingLabels.forEach((label, index) => {
                const starValue = index + 1;
                if (starValue <= rating) {
                    label.classList.add('text-yellow-400');
                    label.classList.remove('text-gray-300');
                } else {
                    label.classList.add('text-gray-300');
                    label.classList.remove('text-yellow-400');
                }
            });
        }

        function getSelectedRating() {
            const checkedInput = document.querySelector('.rating-input input:checked');
            return checkedInput ? parseInt(checkedInput.value) : 0;
        }

        // Initialize on load
        highlightStars(getSelectedRating());

        // Add click to labels
        ratingLabels.forEach((label, index) => {
            const starValue = index + 1;
            const input = document.getElementById(`star${starValue}`);

            // When label is clicked
            label.addEventListener('click', () => {
                input.checked = true; // manually check the input
                highlightStars(starValue); // update stars
            });

            // Hover effect (optional)
            label.addEventListener('mouseenter', () => highlightStars(starValue));
            label.addEventListener('mouseleave', () => highlightStars(getSelectedRating()));
        });
    });
    </script>

{{-- @endpush --}}
