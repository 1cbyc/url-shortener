import { useEffect, useState } from "react";

type Url = {
  id: number;
  code: string;
  url: string;
  created_at: string;
};

type Props = {
  user: string;
  onShowAnalytics: (code: string) => void;
};

export default function UserDashboard({ user, onShowAnalytics }: Props) {
  const [urls, setUrls] = useState<Url[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    setLoading(true);
    fetch("/api/user/urls", { credentials: "include" })
      .then(res => res.json())
      .then(data => {
        setUrls(data.urls || []);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load your links");
        setLoading(false);
      });
  }, [user]);

  const handleDelete = async (id: number) => {
    if (!window.confirm("Delete this short URL?")) return;
    await fetch(`/api/user/urls/${id}`, { method: "DELETE", credentials: "include" });
    setUrls(urls.filter(u => u.id !== id));
  };

  return (
    <div className="w-full mt-4 md:mt-8 flex flex-col gap-4 md:gap-6">
      {/* Summary Card */}
      <div className="w-full bg-orange-50 border border-orange-200 rounded-2xl shadow p-4 sm:p-6 flex flex-col items-center mb-2">
        <div className="text-2xl md:text-3xl font-extrabold text-orange-600">{urls.length}</div>
        <div className="text-slate-700 font-semibold text-sm md:text-base">Total Shortened Links</div>
      </div>
      <div className="w-full bg-white border border-slate-200 rounded-2xl shadow p-2 sm:p-4">
        <h2 className="text-lg md:text-xl font-bold mb-2 md:mb-4 text-slate-900">Your Shortened Links</h2>
        {loading ? (
          <div className="text-slate-500">Loading...</div>
        ) : error ? (
          <div className="text-red-500">{error}</div>
        ) : urls.length === 0 ? (
          <div className="text-slate-500">You have not shortened any links yet.</div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full text-xs md:text-sm border border-slate-200 rounded-lg">
              <thead>
                <tr className="bg-slate-100">
                  <th className="px-3 py-2 text-left">Short Code</th>
                  <th className="px-3 py-2 text-left">Original URL</th>
                  <th className="px-3 py-2 text-left">Created</th>
                  <th className="px-3 py-2 text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                {urls.map((url, idx) => (
                  <tr key={url.id} className={(idx % 2 === 0 ? "bg-white" : "bg-slate-50") + " border-t border-slate-200 group hover:bg-orange-50 transition-colors duration-150"}>
                    <td className="px-3 py-2 font-mono text-orange-600">{url.code}</td>
                    <td className="px-3 py-2 max-w-xs truncate">{url.url}</td>
                    <td className="px-3 py-2 whitespace-nowrap">{new Date(url.created_at).toLocaleString()}</td>
                    <td className="px-3 py-2 flex gap-2">
                      <button onClick={() => onShowAnalytics(url.code)} aria-label={`View analytics for ${url.code}`} title="View analytics for this link" className="px-3 py-1 bg-orange-100 hover:bg-orange-200 text-orange-700 rounded-lg text-xs font-bold shadow-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">View Analytics</button>
                      <button onClick={() => handleDelete(url.id)} aria-label={`Delete link ${url.code}`} title="Delete this link" className="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg text-xs font-bold shadow-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Delete</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
} 