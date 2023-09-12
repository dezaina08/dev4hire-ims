<script setup>
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { Link, useForm, usePage } from '@inertiajs/vue3';
    import { ref } from 'vue'

    defineProps({
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    });

    const user = usePage().props.auth.user;

    const form = useForm({
        photo: null,
        remove_photo: false,
        name: user.name,
        username: user.username,
        email: user.email,
    });

    function submitForm() {
        form.post(route('profile.update'))
    }

    const preview = ref(usePage().props.photo_url)

    function previewImage(event) {

        let input = event.target;
        if (input.files.length) {
            let reader = new FileReader();
            reader.onload = (e) => {
                preview.value = e.target.result;
            }
            form.photo = input.files[0];
            reader.readAsDataURL(input.files[0]);
            form.remove_photo = false
        }
    }

    function browseFile() {
        document.getElementById("photo").click()
    }

    function resetFile() {
        form.photo = null
        preview.value = null
        form.remove_photo = true
    }
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="submitForm()" class="mt-6 space-y-6" enctype="multipart/form-data">
            <div class="md:grid md:grid-cols-2 md:gap-x-6 space-y-6 md:space-y-0">
                <div>
                    <InputLabel for="photo" value="Photo" class="mb-1" />

                    <input
                        type="file"
                        accept="image/*"
                        @input="previewImage"
                        id="photo"
                        name="photo"
                        hidden
                    >

                    <div v-if="preview" class="mb-2">
                        <img :src="preview" class="w-44 h-44 object-cover rounded-full border-4 border-gray-300" />
                        <div v-if="!!form.photo" class="text-sm text-gray-500">
                            <p class="mb-0">File name: {{ form.photo.name }}</p>
                            <p class="mb-0">Size: {{ (form.photo.size/1024).toFixed(2) }}KB</p>
                        </div>
                    </div>

                    <div v-else class="mb-3">
                        <div class="w-44 h-44 rounded-full bg-green-200 flex justify-center items-center border-4 border-gray-300 text-7xl text-green-700">
                            {{ usePage().props.auth.user.name[0] }}
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="browseFile()"
                            type="button"
                            class="h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-3 text-sm"
                        >
                            Browse File
                        </button>
                        <button
                            v-if="preview"
                            @click="resetFile()"
                            type="button"
                            class="h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 text-sm"
                        >
                            Remove
                        </button>
                    </div>

                    <InputError class="mt-2" :message="form.errors.photo" />
                </div>
            </div>

            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="username" value="Username" />

                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
