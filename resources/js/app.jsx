import './bootstrap';
import '../css/app.css'

import { createRoot } from 'react-dom/client';
import PostsIndex from "./Pages/Posts";
const root = createRoot(document.getElementById('root'));
root.render(<PostsIndex />);
