import { Link, router, usePage } from '@inertiajs/react';

export default function MainLayout({ children }) {
    const { auth, flash } = usePage().props;
    const user = auth?.user;

    function logout() {
        router.post(route('logout'));
    }

    return (
        <div className="min-h-screen bg-gray-50">
            <nav className="bg-white border-b border-gray-200 shadow-sm">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16 items-center">
                        <Link
                            href={route('posts.index')}
                            className="text-xl font-bold text-indigo-600 hover:text-indigo-800"
                        >
                            Ominimo Blog
                        </Link>

                        <div className="flex items-center gap-4">
                            {user ? (
                                <>
                                    <span className="text-sm text-gray-600">
                                        {user.name}
                                        {user.is_admin && (
                                            <span className="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Admin
                                            </span>
                                        )}
                                    </span>
                                    <Link
                                        href={route('posts.create')}
                                        className="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-md transition"
                                    >
                                        New Post
                                    </Link>
                                    <button
                                        onClick={logout}
                                        className="text-sm text-gray-500 hover:text-gray-700"
                                    >
                                        Logout
                                    </button>
                                </>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="text-sm text-gray-600 hover:text-gray-900"
                                    >
                                        Login
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-md transition"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            {flash?.success && (
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div className="rounded-md bg-green-50 p-4 text-sm text-green-800 border border-green-200">
                        {flash.success}
                    </div>
                </div>
            )}

            {flash?.error && (
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div className="rounded-md bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                        {flash.error}
                    </div>
                </div>
            )}

            <main className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {children}
            </main>
        </div>
    );
}
