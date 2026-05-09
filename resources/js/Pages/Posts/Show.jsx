import { Link, useForm, router, usePage } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

function CommentItem({ comment, postOwnerId }) {
    const { auth } = usePage().props;
    const user = auth?.user;

    const canDelete =
        user &&
        (user.is_admin ||
            user.id === comment.user_id ||
            user.id === postOwnerId);

    function handleDelete() {
        if (!confirm('Delete this comment?')) return;
        router.delete(route('comments.destroy', comment.id));
    }

    return (
        <div className="flex gap-3 py-4 border-b border-gray-100 last:border-0">
            <div className="flex-1">
                <div className="flex items-center justify-between">
                    <span className="text-sm font-medium text-gray-800">
                        {comment.user?.name ?? 'Guest'}
                    </span>
                    <div className="flex items-center gap-3">
                        <span className="text-xs text-gray-400">
                            {new Date(comment.created_at).toLocaleDateString()}
                        </span>
                        {canDelete && (
                            <button
                                onClick={handleDelete}
                                className="text-xs text-red-500 hover:text-red-700"
                            >
                                Delete
                            </button>
                        )}
                    </div>
                </div>
                <p className="mt-1 text-gray-700 text-sm">{comment.comment}</p>
            </div>
        </div>
    );
}

function CommentForm({ postId }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        comment: '',
    });

    function submit(e) {
        e.preventDefault();
        post(route('comments.store', postId), {
            onSuccess: () => reset('comment'),
        });
    }

    return (
        <form onSubmit={submit} className="mt-6">
            <label className="block text-sm font-medium text-gray-700 mb-1">
                Leave a comment
            </label>
            <textarea
                value={data.comment}
                onChange={(e) => setData('comment', e.target.value)}
                rows={3}
                className="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                placeholder="Write your comment..."
            />
            {errors.comment && (
                <p className="mt-1 text-xs text-red-500">{errors.comment}</p>
            )}
            <button
                type="submit"
                disabled={processing}
                className="mt-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 disabled:opacity-50 transition"
            >
                {processing ? 'Posting...' : 'Post Comment'}
            </button>
        </form>
    );
}

export default function Show({ post, canEdit, canDelete }) {
    function handleDelete() {
        if (!confirm('Are you sure you want to delete this post?')) return;
        router.delete(route('posts.destroy', post.id));
    }

    return (
        <MainLayout>
            <article className="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <div className="flex items-start justify-between">
                    <h1 className="text-3xl font-bold text-gray-900">{post.title}</h1>

                    {(canEdit || canDelete) && (
                        <div className="flex gap-2 ml-4">
                            {canEdit && (
                                <Link
                                    href={route('posts.edit', post.id)}
                                    className="px-3 py-1.5 text-sm border border-gray-300 rounded hover:bg-gray-50 transition"
                                >
                                    Edit
                                </Link>
                            )}
                            {canDelete && (
                                <button
                                    onClick={handleDelete}
                                    className="px-3 py-1.5 text-sm border border-red-300 text-red-600 rounded hover:bg-red-50 transition"
                                >
                                    Delete
                                </button>
                            )}
                        </div>
                    )}
                </div>

                <div className="mt-2 flex items-center gap-4 text-sm text-gray-400">
                    <span>By {post.user?.name ?? 'Unknown'}</span>
                    <span>
                        {new Date(post.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                        })}
                    </span>
                </div>

                <div className="mt-6 prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {post.content}
                </div>
            </article>

            <section className="mt-8">
                <h2 className="text-xl font-semibold text-gray-900 mb-4">
                    Comments ({post.comments?.length ?? 0})
                </h2>

                {post.comments?.length > 0 ? (
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200 px-6 divide-y divide-gray-100">
                        {post.comments.map((comment) => (
                            <CommentItem
                                key={comment.id}
                                comment={comment}
                                postOwnerId={post.user_id}
                            />
                        ))}
                    </div>
                ) : (
                    <p className="text-gray-500 text-sm">No comments yet.</p>
                )}

                <div className="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <CommentForm postId={post.id} />
                </div>
            </section>

            <div className="mt-6">
                <Link
                    href={route('posts.index')}
                    className="text-sm text-indigo-600 hover:underline"
                >
                    ← Back to all posts
                </Link>
            </div>
        </MainLayout>
    );
}
