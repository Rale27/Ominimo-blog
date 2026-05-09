import { useForm, Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function Edit({ post }) {
    const { data, setData, put, processing, errors } = useForm({
        title: post.title,
        content: post.content,
    });

    function submit(e) {
        e.preventDefault();
        put(route('posts.update', post.id));
    }

    return (
        <MainLayout>
            <div className="max-w-2xl mx-auto">
                <h1 className="text-2xl font-bold text-gray-900 mb-6">Edit Post</h1>

                <form onSubmit={submit} className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-5">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Title <span className="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                            className="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        />
                        {errors.title && (
                            <p className="mt-1 text-xs text-red-500">{errors.title}</p>
                        )}
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Content <span className="text-red-500">*</span>
                        </label>
                        <textarea
                            value={data.content}
                            onChange={(e) => setData('content', e.target.value)}
                            rows={10}
                            className="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        />
                        {errors.content && (
                            <p className="mt-1 text-xs text-red-500">{errors.content}</p>
                        )}
                    </div>

                    <div className="flex items-center gap-3 pt-2">
                        <button
                            type="submit"
                            disabled={processing}
                            className="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 disabled:opacity-50 transition"
                        >
                            {processing ? 'Saving...' : 'Save Changes'}
                        </button>
                        <Link
                            href={route('posts.show', post.id)}
                            className="text-sm text-gray-500 hover:text-gray-700"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </MainLayout>
    );
}
