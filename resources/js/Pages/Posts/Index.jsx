import { Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function Index({ posts }) {
    return (
        <MainLayout>
            <div className="flex justify-between items-center mb-8">
                <h1 className="text-3xl font-bold text-gray-900">Blog Posts</h1>
            </div>

            {posts.data.length === 0 ? (
                <div className="text-center py-16 text-gray-500">
                    <p className="text-lg">No posts yet.</p>
                    <Link
                        href={route('posts.create')}
                        className="mt-4 inline-block text-indigo-600 hover:underline"
                    >
                        Be the first to write one!
                    </Link>
                </div>
            ) : (
                <div className="space-y-6">
                    {posts.data.map((post) => (
                        <article
                            key={post.id}
                            className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition"
                        >
                            <Link href={route('posts.show', post.id)}>
                                <h2 className="text-xl font-semibold text-gray-900 hover:text-indigo-600 transition">
                                    {post.title}
                                </h2>
                            </Link>
                            <p className="mt-2 text-gray-600 text-sm line-clamp-2">
                                {post.content}
                            </p>
                            <div className="mt-4 flex items-center gap-4 text-xs text-gray-400">
                                <span>By {post.user?.name ?? 'Unknown'}</span>
                                <span>
                                    {new Date(post.created_at).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                    })}
                                </span>
                                <span>{post.comments_count} comment{post.comments_count !== 1 ? 's' : ''}</span>
                            </div>
                        </article>
                    ))}
                </div>
            )}

            {/* Pagination */}
            {posts.links && posts.last_page > 1 && (
                <div className="mt-8 flex justify-center gap-1">
                    {posts.links.map((link, i) => (
                        <Link
                            key={i}
                            href={link.url ?? '#'}
                            className={`px-3 py-1.5 rounded text-sm border transition ${
                                link.active
                                    ? 'bg-indigo-600 text-white border-indigo-600'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                            } ${!link.url ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''}`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ))}
                </div>
            )}
        </MainLayout>
    );
}
