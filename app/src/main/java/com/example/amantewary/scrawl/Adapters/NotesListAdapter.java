package com.example.amantewary.scrawl.Adapters;

import android.content.Context;
import android.content.Intent;
import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;

import com.example.amantewary.scrawl.Handlers.NoteHandler;
import com.example.amantewary.scrawl.R;
import com.example.amantewary.scrawl.SessionManager;
import com.example.amantewary.scrawl.ViewNotesActivity;
import com.l4digital.fastscroll.FastScroller;

import java.util.ArrayList;
import java.util.List;


public class NotesListAdapter extends RecyclerView.Adapter<ViewHolder> implements FastScroller.SectionIndexer, Filterable {


    private Context context;
    private List<NoteHandler> notesList;
    private List<NoteHandler> notesListFilter;
    private SessionManager sessionManager;

    public NotesListAdapter(Context context, List<NoteHandler> notesList) {
        this.context = context;
        this.notesList = notesList;
        this.notesListFilter = notesList;
        sessionManager = new SessionManager(context.getApplicationContext());
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        return new ViewHolder(LayoutInflater.from(context).inflate(R.layout.note_item, parent, false));
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        final NoteHandler notes = notesListFilter.get(position);
        holder.title.setText(notes.getTitle());
        holder.label.setText(notes.getLabel_name());
        if (!sessionManager.getUserId().equals(notes.getUser_id())){
            String shared_from = String.valueOf(notes.getUser_id());
            holder.shared_by.setText("Shared by other");
        }
        holder.parentView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(context, ViewNotesActivity.class);
                intent.putExtra("noteid", notes.getId());
                context.startActivity(intent);
            }
        });
    }

    @Override
    public int getItemCount() {
        return this.notesListFilter.size();
    }

    @Override
    public String getSectionText(int position) {
        if (notesList.get(position).getTitle().equals("") || notesList.get(position).getTitle() == null) {
            return "a";
        } else {
            return String.valueOf(notesList.get(position).getTitle().charAt(0));
        }
    }

    @Override
    public Filter getFilter() {
        return new Filter() {
            @Override
            protected FilterResults performFiltering(CharSequence charSequence) {
                String charString = charSequence.toString();
                Log.e("Notes", charString);

                if (charString.isEmpty()) {
                    notesListFilter = notesList;
                } else {
                    List<NoteHandler> filteredList = new ArrayList<>();
                    for (NoteHandler row : notesList) {

                        if (row.getLabel_name().toLowerCase().contains(charString.toLowerCase()) || row.getBody().contains(charSequence)
                                || row.getTitle().toLowerCase().contains(charString.toLowerCase())) {
                            filteredList.add(row);
                        }
                    }

                    notesListFilter = filteredList;
                }

                FilterResults filterResults = new FilterResults();
                filterResults.values = notesListFilter;
                return filterResults;
            }

            @Override
            protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
                notesListFilter = (ArrayList<NoteHandler>) filterResults.values;
                notifyDataSetChanged();
            }
        };
    }
}

