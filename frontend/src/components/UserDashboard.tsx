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
    <div className="w-full mt-8">
      <h2 className="text-xl font-bold mb-4 text-slate-900">Your Shortened Links</h2>
      {loading ? (
        <div className="text-slate-500">Loading...</div>
      ) : error ? (
        <div className="text-red-500">{error}</div>
      ) : urls.length === 0 ? (
        <div className="text-slate-500">You have not shortened any links yet.</div>
      ) : (
        <div className="overflow-x-auto">
          <table className="min-w-full text-sm border border-slate-200 rounded-lg">
            <thead>
              <tr className="bg-slate-100">
                <th className="px-3 py-2 text-left">Short Code</th>
                <th className="px-3 py-2 text-left">Original URL</th>
                <th className="px-3 py-2 text-left">Created</th>
                <th className="px-3 py-2 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              {urls.map(url => (
                <tr key={url.id} className="border-t border-slate-200">
                  <td className="px-3 py-2 font-mono text-orange-600">{url.code}</td>
                  <td className="px-3 py-2 max-w-xs truncate">{url.url}</td>
                  <td className="px-3 py-2 whitespace-nowrap">{new Date(url.created_at).toLocaleString()}</td>
                  <td className="px-3 py-2 flex gap-2">
                    <button onClick={() => onShowAnalytics(url.code)} className="px-2 py-1 bg-slate-200 hover:bg-orange-100 text-orange-600 rounded text-xs font-semibold">Analytics</button>
                    <button onClick={() => handleDelete(url.id)} className="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded text-xs font-semibold">Delete</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
} 