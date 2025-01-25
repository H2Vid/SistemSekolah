<script src="{{ asset('assets/static/js/components/dark.js') }}" data-navigate-once="true"></script>
<script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}" data-navigate-once="true"></script>
<script src="https://unpkg.com/feather-icons" data-navigate-once="true"></script>
<script src="{{ asset('assets/compiled/js/app.js') }}" data-navigate-once="true"></script>


<script data-navigate-once="true">

    document.addEventListener("livewire:navigated", function() {

        const btn_logout = document.getElementById('btn-logout')
        btn_logout.addEventListener('click', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: ``,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('auth.logout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        window.location.href = '/auth/login'
                    })
                }
            })
        })

        const btn_logout2 = document.getElementById('btn-logout-2')
        btn_logout2.addEventListener('click', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: ``,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('auth.logout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        window.location.href = '/auth/login'
                    })
                }
            })
        })

        const btn_profil = document.getElementById('btn-profil')
        btn_profil.addEventListener('click', () => {
            window.location.href = '/cms/profile'
        })

        const sidebar = document.getElementById('sidebar')
        const toogleSideBar = document.getElementById('sidebar-toggle')
        const hideSideBar = document.getElementsByClassName('sidebar-hide')[0]
        const body = document.getElementsByTagName('body')[0]

        // Backdrop
        const divDrop = document.createElement('div')
        divDrop.classList.add('sidebar-backdrop')

        let active = false

        toogleSideBar.addEventListener('click', () => {
            active = true
            sidebar.classList.remove('inactive')
            sidebar.classList.remove('active')
            sidebar.classList.add(active ? 'active' : 'inactive')
            body.style.overflowY = active ? 'hidden' : 'auto'
            body.appendChild(divDrop)
        })

        hideSideBar.addEventListener('click', () => {
            active = false

            sidebar.classList.remove('inactive')
            sidebar.classList.remove('active')
            sidebar.classList.add(active ? 'active' : 'inactive')
            body.style.overflowY = active ? 'hidden' : 'auto'
            body.removeChild(divDrop)
        })

        feather.replace()
    });

    document.addEventListener('livewire:initialized', () => {
        // Toast initialization
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Livewire.hook('morph.updated', ({ el, component }) => {
            feather.replace()
        })

        Livewire.on('alert', params => {
            Toast.fire({
                icon: params.type,
                title: params.message
            })
        })

        Livewire.on('confirm', params => {
            Swal.fire({
                title: params.title ?? 'Are you sure?',
                text: params.message ?? `You won't be able to revert this`,
                icon: params.icon ?? 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(params.function, {id: params.id})
                }
            })
        })

        Livewire.on('closeModal', params => {
            const modal = document.getElementById(params.modal)
            const modalBackdrop = document.getElementsByClassName('modal-backdrop')[0]

            modal?.classList?.remove('show')
            modalBackdrop?.remove()
        })

        // document.getElementsByClassName('currency')[0].addEventListener('input', function(e) {
        //     let cursorPosition = this.selectionStart;
        //     let sanitizedValue = parseInt(this.value.replace(/[^,\d]/g, ''));
        //     let originalLength = this.value.length;

        //     if (isNaN(sanitizedValue)) {
        //         this.value = "";
        //     } else {
        //         this.value = sanitizedValue.toLocaleString('id-ID', {
        //             currency: 'IDR',
        //             style: 'currency',
        //             minimumFractionDigits: 0
        //         });
        //         cursorPosition = this.value.length - originalLength + cursorPosition;
        //         this.setSelectionRange(cursorPosition, cursorPosition);
        //     }
        // });
        
        // select2
        // $('.select2').select2();        
    })

    document.addEventListener('livewire:load', function () {
        Livewire.on('showNotification', (data) => {
            // Show notification using your preferred method/library
            // For example, with Alpine.js:
            alert(1)
        });
    });
</script>
